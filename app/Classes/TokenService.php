<?php

namespace App\Classes;

use App\Classes\Dto\Response\ResponseData;
use App\Classes\Dto\Response\ResponseOrderData;
use App\Classes\Dto\Response\Scan;
use App\Classes\Dto\Response\SessionResponse;
use App\Classes\Dto\Response\SessionSummary;
use App\Classes\Dto\Response\SessionTransaction;
use App\Classes\Dto\Response\Trip;
use App\Classes\Dto\Response\TripOrder;
use App\Exceptions\RidlrException;
use App\Models\QRFareTable;
use App\Models\UserLoginInfo;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Log;

class TokenService
{

    public function __construct()
    {
        //
    }

    public function generateResponseDataFromBooking($records, $forOrder = false)
    {
        try {
            $masterBooking = $records['master'];
            $slaveBookings = $records['slave'];
            if ($forOrder) {
                $response = $this->getResponseOrderData($masterBooking);
            } else {
                $response = $this->getResponseData($masterBooking, $slaveBookings);
            }
            return $response;
        } catch (RidlrException $r) {
            Log::error($r);
            throw $r;
        }
    }

    private function getResponseOrderData($masterBooking)
    {
        $responseData = new ResponseOrderData();
        $masterBalance = ['amount' => 0, 'trip' => 0];
        if (!in_array($masterBooking->qr_type_id, array(1, 2))) {
            $balanceOp = new BalanceOperation();
            $masterBalance = $balanceOp->getMasterBalance($masterBooking->master_qr_code_number);
        }
        $trips = $this->getTokensForOrder($masterBooking->slave_bookings(), $masterBooking);
        $responseData->master_qr_code_number = $masterBooking->master_qr_code_number;
        $responseData->master_accounting_id = $masterBooking->master_accounting_id;
        $responseData->operator_id = $masterBooking->operator_id;
        $responseData->pass_price = $masterBooking->charged_amount;
        $responseData->remaining_amount = $masterBalance['amount'];
        $responseData->remaining_trips = $masterBalance['trip'];
        $responseData->support_type_id = $masterBooking->support_type_id;
        $responseData->qr_type_id = $masterBooking->qr_type_id;
        $responseData->pass_id = $masterBooking->pass_id;
        $responseData->operation_type_id = $masterBooking->operation_type_id;
        $responseData->record_date = strtotime($masterBooking->record_date);
        $responseData->travel_date = strtotime($masterBooking->record_date);
        $responseData->master_qr_code_expiry_date = strtotime($masterBooking->master_qr_code_expiry_date);
        $responseData->trips = $trips;
        return $responseData;
    }

    private function getTokensForOrder($slaveBookings, $masterBooking)
    {
        $transactionId = $masterBooking->master_qr_code_number;
        $tokenDataArray = array();
        $tokenDataArray['operatorId'] = $masterBooking->operator_id;
        $tokenDataArray['fare'] = $masterBooking->charged_amount;
        $tokenDataArray['supportType'] = $masterBooking->support_type_id;
        $tokenDataArray['qrType'] = $masterBooking->qr_type_id;
        $tokenDataArray['tokenType'] = $masterBooking->pass_id;
        $trips = array();
        $dbOp = new DBOperations();
        foreach ($slaveBookings as $slaveBooking) {
            $trip = new TripOrder();
            $penaltyToken = ($slaveBooking->master_qr_code_number != $slaveBooking->reference_qr_code_number);
            $type = "";
            if ($penaltyToken) {
                $type = "PENALTY";
            } elseif ($slaveBooking->qr_type_id == 1) {
                $type = "OUTWARD";
            } elseif ($slaveBooking->qr_type_id == 2) {
                $type = "RETURN";
            } elseif ($slaveBooking->qr_type_id == 3) {
                $type = "STORE_VALUE";
            } elseif ($slaveBooking->qr_type_id == 4) {
                $type = "TRIP_PASS";
            }
            if ($penaltyToken) {
                $type = "PENALTY";
            } else if ($slaveBooking->qr_type_id == 1 || $slaveBooking->qr_type_id == 2) {
                $fare = new QRFareTable();
                $extendedDestination = $fare->getExtendedDestinationForSameFare($slaveBooking->issue_origin_station_id, $slaveBooking->issue_destin_station_id, $slaveBooking->pass_id);
                $trip->extendedDestination = $extendedDestination;
                $masterBooking->extendedDestination = $extendedDestination;
                $type = ($slaveBooking->issue_origin_station_id == $masterBooking->issue_origin_station_id) ? 'OUTWARD' : 'RETURN';
            }
            $tokenDataArray['source'] = $slaveBooking->issue_origin_station_id;
            $tokenDataArray['destination'] = $slaveBooking->issue_destin_station_id;
            $tokenDataJson = json_decode(json_encode($tokenDataArray));
            $latestTokenStatus = $dbOp->getLatestStatusAndExpiry($slaveBooking);
            $slaveExpiryTimestamp = $latestTokenStatus['slaveExpiry'];
            $tokenStatus = $latestTokenStatus['tokenStatus'];
            $entryScanTimestamp = $dbOp->getEntryScanTimeForSlave($slaveBooking->slave_qr_code_number);
            $masterExpiryTimestamp = strtotime($masterBooking->master_qr_code_expiry_date);
            if ($slaveBooking->qr_type_id == 3 || $slaveBooking->qr_type_id == 4) {
                $issueTime = strtotime($slaveBooking->record_date);
                $firstPart = $this->getEncryptedQrCodeData($tokenDataJson, $slaveBooking->slave_qr_code_number, $masterExpiryTimestamp * 1000, $transactionId);
                $encryptedQRCodeData = $firstPart . $this->getEncryptedQrCodeDataSecondPart($slaveExpiryTimestamp, $issueTime);
            } else {
                $encryptedQRCodeData = $this->getEncryptedQrCodeData($tokenDataJson, $slaveBooking->slave_qr_code_number, $masterExpiryTimestamp * 1000, $transactionId);
            }
            $trip->qr_code_data = $encryptedQRCodeData;
            $trip->expiryTime = $slaveExpiryTimestamp;
            $trip->record_date = strtotime($slaveBooking->record_date);
            $trip->qr_status = $tokenStatus;
            $trip->slave_qr_code_number = $slaveBooking->slave_qr_code_number;
            $trip->issue_origin_station_id = $slaveBooking->issue_origin_station_id;
            $trip->issue_destination_station_id = $slaveBooking->issue_destin_station_id;
            array_push($trips, $trip);
        }
        return $trips;
    }

    public function getEncryptedQrCodeData($tokenDataJson, $txId, $expiryTimestamp, $masterTxnId): string
    {
        $qrCodeData = array();
        $qrCodeData['e'] = (string)$expiryTimestamp;
        $qrCodeData['p'] = (string)$tokenDataJson->tokenType;
        $qrCodeData['o'] = (string)$tokenDataJson->operatorId;
        $qrCodeData['t'] = (string)$txId;
        $qrCodeData['m'] = $masterTxnId;
        $qrCodeData['f'] = (string)$tokenDataJson->fare;
        $qrCodeData['r'] = 0;
        $qrCodeData['w'] = $tokenDataJson->supportType;
        if ($tokenDataJson->qrType != 3) {
            if (isset($tokenDataJson->source)) {
                $qrCodeData['s'] = (string)$tokenDataJson->source;
            }
            if (isset($tokenDataJson->destination)) {
                $qrCodeData['d'] = isset($tokenDataJson->extendedDestination) ? (string)$tokenDataJson->extendedDestination : (string)$tokenDataJson->destination;
            }
        }
        if (isset($tokenDataJson->isPenaltyToken)) {
            $qrCodeData['s'] = 0;
            $qrCodeData['r'] = 1;
            $qrCodeData['d'] = isset($tokenDataJson->extendedDestination) ? (string)$tokenDataJson->extendedDestination : (string)$tokenDataJson->destination;
        }
        $qrCodeData = json_encode($qrCodeData, JSON_NUMERIC_CHECK);
        //$qrCodeData = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$qrCodeData);
        // Encrypt the qrCodeData...
        $mcrypt = new Mcrypt;
        $encryptedQRCodeData = $mcrypt->encrypt($qrCodeData);
        $encryptedQRCodeData .= "~";
        return $encryptedQRCodeData;
    }

    public function getEncryptedQrCodeDataSecondPart($expiryTime, $issueTime): string
    {
        $secondPart = ($expiryTime * 1000) . "|" . $issueTime;
        $mcrypt = new Mcrypt;
        $secondPart = $mcrypt->encrypt2($secondPart) . "~";
        return $secondPart;
    }

    public function getResponseData($masterBooking, $slaveBookings)
    {
        $responseData = new ResponseData();
        $masterBalance = ['amount' => 0, 'trip' => 0];
        if (!in_array($masterBooking->qr_type_id, array(1, 2))) {
            $balanceOp = new BalanceOperation();
            $masterBalance = $balanceOp->getMasterBalance($masterBooking->master_qr_code_number);
        }

        $factory = new TokenFactory();
        $tokenService = $factory->getTokenService($masterBooking->support_type_id);
        $trips = $tokenService->getTokens($slaveBookings, $masterBooking);
        $responseData->masterTxnId = $masterBooking->master_qr_code_number;
        $responseData->transactionId = $masterBooking->master_accounting_id;
        //$responseData->refAccountingId = $masterBooking->master_accounting_id;
        $responseData->refTxnId = $masterBooking->refTxnId;
        $responseData->operatorId = $masterBooking->operator_id;
        $responseData->amount = $masterBooking->charged_amount;
        $responseData->registrationFee = $masterBooking->registration_fee;
        $responseData->balance = $masterBalance['amount'];
        $responseData->balanceTrip = $masterBalance['trip'];
        $responseData->supportType = $masterBooking->support_type_id;
        $responseData->qrType = $masterBooking->qr_type_id;
        $responseData->tokenType = $masterBooking->pass_id;
        $responseData->originalTokenType = $masterBooking->originalTokenType;
        $responseData->operationTypeId = $masterBooking->operation_type_id;
        $responseData->timestamp = strtotime($masterBooking->record_date);
        $responseData->travelDate = strtotime($masterBooking->record_date);
        $responseData->masterExpiry = strtotime($masterBooking->master_qr_code_expiry_date);
        $responseData->trips = $trips;
        if (isset($masterBooking->isPenaltyToken)) {
            $responseData->expiry = getenv('PENALTY_EXPIRY');
            $responseData->timestamp = time();
            $responseData->entryScanTime = $masterBooking->entryScanTime;
            $responseData->entryScanStation = $masterBooking->entryScanStation;
            $responseData->exitUptoStation = $masterBooking->exitUptoStation;
            $responseData->exitScanExpiryTime = $masterBooking->exitScanExpiryTime;
            $responseData->travelDate = time();
            $responseData->penalties = $masterBooking->penalties;
            $responseData->overTravelCharges = $masterBooking->overTravelCharges;
            $responseData->operatorTransactionId = $masterBooking->operatorTransactionId;
            if (isset($responseData->operatorTransactionId)) {
                unset($responseData->expiry);
                unset($responseData->entryScanTime);
                unset($responseData->entryScanStation);
                unset($responseData->exitUptoStation);
                unset($responseData->exitScanExpiryTime);
                unset($responseData->penalties);
                unset($responseData->overTravelCharges);
            } else {
                unset($responseData->masterExpiry);
                unset($responseData->trips);
            }
            unset($responseData->operatorTransactionId);
        } else {
            unset($responseData->refTxnId);
            unset($responseData->originalTokenType);
            unset($responseData->expiry);
            unset($responseData->entryScanTime);
            unset($responseData->entryScanStation);
            unset($responseData->exitUptoStation);
            unset($responseData->exitScanExpiryTime);
            unset($responseData->penalties);
            unset($responseData->overTravelCharges);
        }
        return $responseData;
    }

    public function getTokens($slaveBookings, $masterBooking)
    {
        $transactionId = $masterBooking->master_qr_code_number;
        $tokenDataArray = array();
        $tokenDataArray['operatorId'] = $masterBooking->operator_id;
        $tokenDataArray['fare'] = $masterBooking->charged_amount;
        $tokenDataArray['supportType'] = $masterBooking->support_type_id;
        $tokenDataArray['qrType'] = $masterBooking->qr_type_id;
        $tokenDataArray['tokenType'] = $masterBooking->pass_id;
        $tokenDataArray['operationType'] = $masterBooking->operation_type_id;
        if (isset($masterBooking->isPenaltyToken)) {
            $tokenDataArray['isPenaltyToken'] = $masterBooking->isPenaltyToken;
        }
        $trips = array();
        $dbOp = new DBOperations();
        foreach ($slaveBookings as $slaveBooking) {
            $trip = new Trip();
            $penaltyToken = ($slaveBooking->master_qr_code_number != $slaveBooking->reference_qr_code_number);
            $type = "";
            if ($penaltyToken) {
                $type = "PENALTY";
            } elseif ($slaveBooking->qr_type_id == 1) {
                $type = "OUTWARD";
            } elseif ($slaveBooking->qr_type_id == 2) {
                $type = "RETURN";
            } elseif ($slaveBooking->qr_type_id == 3) {
                $type = "STORE_VALUE";
            } elseif ($slaveBooking->qr_type_id == 4) {
                $type = "TRIP_PASS";
            }
            if ($penaltyToken) {
                $type = "PENALTY";
                $tokenDataArray['isPenaltyToken'] = true;
            } else if ($slaveBooking->qr_type_id == 1 || $slaveBooking->qr_type_id == 2) {
                $type = ($slaveBooking->issue_origin_station_id == $masterBooking->issue_origin_station_id) ? 'OUTWARD' : 'RETURN';
                $fare = new QRFareTable();
                $extendedDestination = $fare->getExtendedDestinationForSameFare($slaveBooking->issue_origin_station_id, $slaveBooking->issue_destin_station_id, $slaveBooking->pass_id);
                $trip->extendedDestination = $extendedDestination;
                $masterBooking->extendedDestination = $extendedDestination;
                $tokenDataArray['extendedDestination'] = $masterBooking->extendedDestination;
            }
            $tokenDataArray['source'] = $slaveBooking->issue_origin_station_id;
            $tokenDataArray['destination'] = $slaveBooking->issue_destin_station_id;
            $tokenDataJson = json_decode(json_encode($tokenDataArray));
            $latestTokenStatus = $dbOp->getLatestStatusAndExpiry($slaveBooking);
            $slaveExpiryTimestamp = $latestTokenStatus['slaveExpiry'];
            $tokenStatus = $latestTokenStatus['tokenStatus'];
            $entryScanTimestamp = $dbOp->getEntryScanTimeForSlave($slaveBooking->slave_qr_code_number);
            $masterExpiryTimestamp = strtotime($masterBooking->master_qr_code_expiry_date);
            //$encryptedQRCodeData = $this->getEncryptedQrCodeData($tokenDataJson, $slaveBooking->slave_qr_code_number, $masterExpiryTimestamp * 1000, $transactionId);
            $tokenFactory = new TokenFactory();
            $tokenService = $tokenFactory->getTokenService($masterBooking->support_type_id);
            if ($slaveBooking->qr_type_id == 3 || $slaveBooking->qr_type_id == 4) {
                $issueTime = strtotime($slaveBooking->record_date);
                $firstPart = $tokenService->getEncryptedQrCodeData($tokenDataJson, $slaveBooking->slave_qr_code_number, $masterExpiryTimestamp * 1000, $transactionId);
                $encryptedQRCodeData = $firstPart . $tokenService->getEncryptedQrCodeDataSecondPart($slaveExpiryTimestamp, $issueTime);
            } else {
                $encryptedQRCodeData = $tokenService->getEncryptedQrCodeData($tokenDataJson, $slaveBooking->slave_qr_code_number, $masterExpiryTimestamp * 1000, $transactionId);
            }
            $trip->qrCodeData = $encryptedQRCodeData;
            $trip->expiryTime = $slaveExpiryTimestamp;
            $trip->entryScanTime = $entryScanTimestamp;
            $trip->issueTime = strtotime($slaveBooking->record_date);
            $trip->tokenStatus = $tokenStatus;
            $trip->qrCodeId = $slaveBooking->slave_qr_code_number;
            $trip->transactionId = $slaveBooking->slave_accounting_id;
            $trip->type = $type;
            array_push($trips, $trip);
        }
        return $trips;
    }

    public function login($headers)
    {
        $equipment_id = $headers['EquipmentId'];
        $timestamp = $headers['Timestamp'];
        $shiftId = $headers['ShiftId'];
        $dbOp = new DBOperations;
        $user_id = Auth::user()->user_id;
        try {
            $dbOp->checkUserLoginInfo($user_id, $equipment_id);
        } catch (RidlrException $exception) {
            // Logout previous active session if exists
            if ($exception->getCustomCode() == RidlrException::customCode(RidlrException::ACTIVE_SESSION_EXISTS)) {
                UserLoginInfo::logoutLatestActiveSessionForEquipment($equipment_id, $timestamp);
            } else {
                throw $exception;
            }
        }
        $login_info = $dbOp->insertUserLoginInfo($shiftId, $user_id, $equipment_id, $timestamp);
        $session = new SessionResponse;
        $session->shiftId = $login_info->shift_id;
        $session->userId = $login_info->user_id;
        $session->equipmentId = $login_info->equipment_id;
        $session->recordDate = strtotime($login_info->login_date);
        $session->sessionStartTime = strtotime($login_info->login_date);
        unset($session->sessionEndTime);
        unset($session->activeSessionTime);
        return $session;
    }

    public function logout($headers)
    {
        $shift_id = $headers['ShiftId'];
        $user_id = Auth::user()->user_id;
        $equipment_id = $headers['EquipmentId'];
        $timestamp = $headers['Timestamp'];
        $dbOp = new DBOperations;
        $login_info = $dbOp->updateUserLoginInfo($shift_id, $user_id, $equipment_id, $timestamp);
        $session = new SessionResponse;
        $session->shiftId = $login_info->shift_id;
        $session->userId = $login_info->user_id;
        $session->equipmentId = $login_info->equipment_id;
        $session->sessionEndTime = strtotime($login_info->logout_date);
        $session->activeSessionTime = (strtotime($login_info->logout_date) - strtotime($login_info->login_date));
        $session->recordDate = strtotime($login_info->logout_date);
        unset($session->sessionStartTime);
        return $session;
    }

    public function getTransactions($headers)
    {
        $shift_id = $headers['ShiftId'];
        $user_id = Auth::user()->user_id;
        $equipment_id = $headers['EquipmentId'];
        $dbOp = new DBOperations;
        $response = [];
        $response['summary'] = [];
        $response['transactions'] = [];
        $login_info = $dbOp->getUserLoginInfo($shift_id, $user_id, $equipment_id);
        foreach ($login_info as $login) {
            $session_summary = new SessionSummary;
            $session_summary->date = $login->record_date;
            $session_summary->sessionStart = $login->session_start;
            $session_summary->sessionEnd = $login->session_end;
            $session_summary->supportType = $login->support_type_id;
            $session_summary->qrType = $login->qr_type_id;
            $session_summary->passType = $login->pass_id;
            $session_summary->saleCount = intval($login->sale_count);
            $session_summary->saleAmount = intval($login->sale_amount);
            $session_summary->shiftId = $login->shift_id;
            $session_summary->userId = $login->user_id;
            $session_summary->equipmentId = $login->equipment_id;
            array_push($response['summary'], $session_summary);
        }
        $transactions = $dbOp->getUserTransactions($shift_id, $user_id, $equipment_id);
        foreach ($transactions as $transaction) {
            $session_transaction = new SessionTransaction;
            $session_transaction->masterQRCodeNumber = $transaction->master_qr_code_number;
            $session_transaction->amount = $transaction->pass_price;
            $session_transaction->units = $transaction->units;
            $session_transaction->origin = $transaction->issue_origin_station_id;
            $session_transaction->destination = $transaction->issue_destin_station_id;
            $session_transaction->recordDate = $transaction->record_date;
            $session_transaction->supportType = $transaction->support_type_id;
            $session_transaction->qrType = $transaction->qr_type_id;
            $session_transaction->passType = $transaction->pass_id;
            array_push($response['transactions'], $session_transaction);
        }
        return $response;
    }

    public function issueToken($tokenDataJson, $paymentDetails)
    {
        switch ($tokenDataJson->qrType) {
            case 1:
                $token = new SingleJourneyToken;
                $response = $token->issueSJTToken($tokenDataJson, $paymentDetails);
                break;
            case 2:
                $token = new ReturnJourneyToken;
                $response = $token->issueRJTToken($tokenDataJson, $paymentDetails);
                break;
        }
        return $response;
    }

    public function issuePass($tokenDataJson, $paymentDetails)
    {
        switch ($tokenDataJson->qrType) {
            case 3:
                $token = new StoreValueToken;
                $response = $token->issueSVToken($tokenDataJson, $paymentDetails);
                break;
            case 4:
                $dbOp = new DBOperations;
                $tokenType = $tokenDataJson->tokenType;
                $pass = $dbOp->getPassDetails($tokenType);
                $bundle_trips = 0;
                if (isset($pass)) {
                    $bundle_trips = $pass->bundle_trips;
                }
                $tokenDataJson->trips = $bundle_trips;
                $token = new TripPassToken;
                $response = $token->issueTPToken($tokenDataJson, $paymentDetails);
                break;
        }
        return $response;
    }

    public function reloadPass($tokenDataJson, $paymentDetails)
    {
        switch ($tokenDataJson->qrType) {
            case 3:
                $token = new StoreValueToken;
                $response = $token->issueSVToken($tokenDataJson, $paymentDetails);
                break;
            case 4:
                $dbOp = new DBOperations;
                if ($this->ifStationsModified($tokenDataJson->masterTxnId, $tokenDataJson->source, $tokenDataJson->destination) || $this->isPassTypeChanged($tokenDataJson->masterTxnId, $tokenDataJson->tokenType)) {
                    $tokenDataJson->operationTypeId = $dbOp->getOperationTypeIdByName('ISSUE');
                }
                $tokenType = $tokenDataJson->tokenType;
                $pass = $dbOp->getPassDetails($tokenType);
                $bundle_trips = 0;
                if (isset($pass)) {
                    $bundle_trips = $pass->bundle_trips;
                }
                $tokenDataJson->trips = $bundle_trips;
                $token = new TripPassToken;
                $response = $token->issueTPToken($tokenDataJson, $paymentDetails);
                break;
        }
        return $response;
    }

    public function ifStationsModified($masterTxnId, $source, $destination)
    {
        $dbOp = new DBOperations;
        $routeDetails = $dbOp->getRouteInfo($masterTxnId);
        if ($routeDetails->issue_origin_station_id == $destination &&
            $routeDetails->issue_destin_station_id == $source) {
            return false;
        }
        if ($routeDetails->issue_origin_station_id != $source ||
            $routeDetails->issue_destin_station_id != $destination) {
            return true;
        }
        return false;
    }

    private function isPassTypeChanged($masterTxnId, $newPassType)
    {
        $dbOp = new DBOperations;
        $masterBookingPassId = $dbOp->getPassType($masterTxnId);
        $oldPassType = $masterBookingPassId->pass_id;
        if ($oldPassType != $newPassType) {
            return true;
        }
    }

    public function issueTrip($tokenDataJson)
    {
        switch ($tokenDataJson->qrType) {
            case 3:
                $token = new StoreValueToken;
                $response = $token->issueSVTrip($tokenDataJson);
                break;
            case 4:
                $token = new TripPassToken;
                $response = $token->issueTPTrip($tokenDataJson);
                break;
        }
        return $response;
    }

    public function generateResponseDataForPenalty($penaltyData, $masterBooking)
    {
        try {
            $token = new PenaltyToken;
            $records = $token->getPenaltyStatusResponse($penaltyData, $masterBooking);
            $masterBooking = $records['master'];
            $slaveBookings = $records['slave'];
            $response = $this->getResponseData($masterBooking, $slaveBookings);
            return $response;
        } catch (RidlrException $r) {
            Log::error($r);
            throw $r;
        }
    }

    public function processScan($scanDataJson)
    {
        // Insert into Validation and update QR Status
        $dbOp = new DBOperations();
        $all_qr_data = $dbOp->addScanRecords($scanDataJson);

        $balanceOp = new BalanceOperation;
        $balanceResponse = $balanceOp->calculateAndDeductBalance($scanDataJson);

        // Only in case of Store Value Exit event, it will go into this flow..
        if (isset($balanceResponse)) {
            $dbOp->updateAmountInfoInSlaveAndAll($balanceResponse, $scanDataJson->transactionId);
        }

        $data = array();
        $data['scanType'] = $scanDataJson->validation_type;
        $data['timeTaken'] = time() * 1000 - $scanDataJson->scan_time;
        $data = json_encode($data);

        $mongoOp = new MongoOperations;
        $mongoOp->logMongo($mongoOp->getMongoDocument(null, $data, $scanDataJson->transactionId, "SCAN_EVENT_BACKEND", time() * 1000, $scanDataJson->equipment_id, null));

        $response = $scanDataJson;
        $response->valid = "TRUE";
        return $response;
    }

    public function setDefaultSupportType(&$tokenDataJson)
    {
        $tokenDataJson->supportType = 3;
    }

    public function setDefaultQrType($passType, &$tokenDataJson)
    {
        $qrType = array(10 => 1, 90 => 2, 81 => 3, 21 => 4);
        if (!in_array($passType, $qrType)) {
            $dbOp = new DBOperations;
            $passDetails = $dbOp->getActivePassDetails($passType);
            if (isset($passDetails)) {
                $tokenDataJson->qrType = $passDetails->qr_type_id;
            }
        } else {
            $tokenDataJson->qrType = $qrType[$passType];
        }
    }

    public function getTokenStatus($id)
    {
        $dbOperation = new DBOperations();
        $response = $dbOperation->getTokenValidation($id);
        if (!isset($response) || $response == null) {
            throw new RidlrException(RidlrException::QR_NOT_SCANNED);
        }
        return $response;
    }

    public function issuePenaltyToken($tokenDataJson, $paymentDetails)
    {
        $penaltyToken = new PenaltyToken();
        return $penaltyToken->issuePenaltyToken($tokenDataJson, $paymentDetails);
    }

    public function getSlaveExpiry($activationTime, $tokenType, $isReturn)
    {
        $dbOperation = new DBOperations();
        if (!$isReturn) {
            $activationTime = new DateTime();
            $activationTime = $activationTime->format('Y-m-d H:i:s');
            return $dbOperation->getSlaveExpiryPaperQR($activationTime, $tokenType);
        } else {
            return $dbOperation->getSlaveExpiryForReturnToken($activationTime, $tokenType);
        }
    }

    private function getScans($slaveBooking)
    {
        $scans = array();
        $qr_validations = $slaveBooking->qr_validations();
        foreach ($qr_validations as $qr_validation) {
            $scan = new Scan();
            $scan->type = ($qr_validation->validation_type == 0 ? "ENTRY" : "EXIT");
            $scan->station = $qr_validation->validation_station_id;
            $scan->scanTime = strtotime($qr_validation->record_date);
            array_push($scans, $scan);
        }
        return $scans;
    }

}
