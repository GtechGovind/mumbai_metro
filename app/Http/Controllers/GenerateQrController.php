<?php

namespace App\Http\Controllers;

use App\Models\ApiModels\Data;
use App\Models\ApiModels\issueTokenModel;
use App\Models\ApiModels\Payment;
use App\Models\Master;
use App\Models\QrData;
use Illuminate\Http\Request;

class GenerateQrController extends Controller
{
    public function GenerateQrCode(Request $request)
    {

        $requestBody = json_decode($request -> getContent());

        $BASE_URL = env("MMOPL_BASE_API_URL");
        $AUTHORIZATION = env("MMOPL_BASE_AUTH_KEY");

        $data = new Data(
            $activationTime = $requestBody->data->activationTime,
            $destination = $requestBody->data->destination,
            $email = $requestBody->data->email,
            $fare = $requestBody->data->fare,
            $mobile = $requestBody->data->mobile,
            $name = $requestBody->data->name,
            $operationTypeId = $requestBody->data->operationTypeId,
            $operatorId = $requestBody->data->operatorId,
            $operatorTransactionId = $requestBody->data->operatorTransactionId,
            $qrType = $requestBody->data->qrType,
            $source = $requestBody->data->source,
            $supportType = $requestBody->data->supportType,
            $tokenType = $requestBody->data->tokenType,
            $trips = $requestBody->data->trips
        );

        $payment = new Payment(
            $pass_price = $requestBody->payment->pass_price,
            $pgId = $requestBody->payment->pgId
        );

        $issueTokenModel = new issueTokenModel(
            $data,
            $payment
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL/qrcode/issueToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($issueTokenModel),
            CURLOPT_HTTPHEADER => [
                "Authorization:  $AUTHORIZATION",
                'Content-Type:  application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $Response = json_decode($response);

        $newMaster = new Master();

        $newMaster->order_no = $requestBody->data->operatorTransactionId;
        $newMaster->master_qr_code = $Response->data->masterTxnId;
        $newMaster->master_acc_id = $Response->data->transactionId;
        $newMaster->phone_number = $requestBody->data->mobile;
        $newMaster->source = $requestBody->data->source;
        $newMaster->destination = $requestBody->data->destination;
        $newMaster->ticket_type = $requestBody->data->tokenType;
        $newMaster->ticket_count = $requestBody->data->trips;
        $newMaster->total_fare = $requestBody->data->fare;
        $newMaster->travel_date = $Response->data->travel_date;
        $newMaster->master_expiry = $Response->data->master_expiry;
        $newMaster->grace_expiry = $Response->data->grace_expiry;
        $newMaster->record_date = $Response->data->record_date;

        (new MasterController)->populateMasterTable($newMaster);

        foreach ($Response->data->trips as $trip) {

            $Qr = new QrData();

            $Qr->order_no = $requestBody->data->operatorTransactionId;
            $Qr->master_qr_code = $Response->data->masterTxnId;
            $Qr->slave_qr_code = $trip->qrCodeId;
            $Qr->slave_acc_id = $trip->transactionId;
            $Qr->phone_number = $requestBody->data->mobile;
            $Qr->source = $requestBody->data->source;
            $Qr->destination = $requestBody->data->destination;
            $Qr->ticket_type = $requestBody->data->tokenType;
            $Qr->qr_direction = $trip->type;
            $Qr->qr_code_data = $trip->qrCodeData;
            $Qr->qr_status = $trip->tokenStatus;
            $Qr->record_date = $trip->record_date;
            $Qr->slave_expiry_date = $trip->expiryTime;

            (new QrDataController)->populateQrData($Qr);

        }

        return $response;
    }

    /*public function GenerateQrCode(Request $requestBody)
    {

        $BASE_URL = env("MMOPL_BASE_API_URL");
        $AUTHORIZATION = env("MMOPL_BASE_AUTH_KEY");

        $data = new Data(
            $activationTime = $requestBody->input('activationTime'),
            $destination = $requestBody->input('destination'),
            $email = $requestBody->input('email'),
            $fare = $requestBody->input('fare'),
            $mobile = $requestBody->input('mobile'),
            $name = $requestBody->input('name'),
            $operationTypeId = $requestBody->input('operationTypeId'),
            $operatorId = $requestBody->input('operatorId'),
            $operatorTransactionId = $requestBody->input('operatorTransactionId'),
            $qrType = $requestBody->input('qrType'),
            $source = $requestBody->input('source'),
            $supportType = $requestBody->input('supportType'),
            $tokenType = $requestBody->input('tokenType'),
            $trips = $requestBody->input('trips')
        );

        $payment = new Payment(
            $pass_price = $requestBody->input('pass_price'),
            $pgId = $requestBody->input('pgId')
        );

        $issueTokenModel = new issueTokenModel(
            $data,
            $payment
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL/qrcode/issueToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($issueTokenModel),
            CURLOPT_HTTPHEADER => [
                "Authorization:  $AUTHORIZATION",
                'Content-Type:  application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $Response = json_decode($response);

        $newMaster = new Master();

        $newMaster->order_no = $requestBody->input('operatorTransactionId');
        $newMaster->master_qr_code = $Response->data->masterTxnId;
        $newMaster->master_acc_id = $Response->data->transactionId;
        $newMaster->phone_number = $requestBody->input('mobile');
        $newMaster->source = $requestBody->input('source');
        $newMaster->destination = $requestBody->input('destination');
        $newMaster->ticket_type = $requestBody->input('tokenType');
        $newMaster->ticket_count = $requestBody->input('trips');
        $newMaster->total_fare = $requestBody->input('fare');
        $newMaster->travel_date = $Response->data->travel_date;
        $newMaster->master_expiry = $Response->data->master_expiry;
        $newMaster->grace_expiry = $Response->data->grace_expiry;
        $newMaster->record_date = $Response->data->record_date;

        (new MasterController)->populateMasterTable($newMaster);

        foreach ($Response->data->trips as $trip) {

            $Qr = new QrData();

            $Qr->order_no = $requestBody->input('operatorTransactionId');
            $Qr->master_qr_code = $Response->data->masterTxnId;
            $Qr->slave_qr_code = $trip->qrCodeId;
            $Qr->slave_acc_id = $trip->transactionId;
            $Qr->phone_number = $requestBody->input('mobile');
            $Qr->source = $requestBody->input('source');
            $Qr->destination = $requestBody->input('destination');
            $Qr->ticket_type = $requestBody->input('tokenType');
            $Qr->qr_direction = $trip->type;
            $Qr->qr_code_data = $trip->qrCodeData;
            $Qr->qr_status = $trip->tokenStatus;
            $Qr->record_date = $trip->record_date;
            $Qr->slave_expiry_date = $trip->expiryTime;

            (new QrDataController)->populateQrData($Qr);

        }

        return $response;
    }*/

}
