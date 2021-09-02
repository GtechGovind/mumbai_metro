<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\QrData;
use Illuminate\Http\Request;

class GenerateQrController extends Controller
{
    public function GenerateQrCode(Request $request)
    {

        $BASE_URL = env("MMOPL_BASE_API_URL");
        $AUTHORIZATION = env("MMOPL_BASE_AUTH_KEY");

        $requestBody = json_decode($request->getContent());

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL/qrcode/issueToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => '',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "data": {
                    "activationTime"        : "' . $requestBody->data->activationTime . '",
                    "destination"           : "' . $requestBody->data->destination . '",
                    "email"                 : "' . $requestBody->data->email . '",
                    "fare"                  : "' . $requestBody->data->fare . '",
                    "mobile"                : "' . $requestBody->data->mobile . '",
                    "name"                  : "' . $requestBody->data->name . '",
                    "operationTypeId"       : "' . $requestBody->data->operationTypeId . '",
                    "operatorId"            : "' . $requestBody->data->operatorId . '",
                    "operatorTransactionId" : "' . $requestBody->data->operatorTransactionId . '",
                    "qrType"                : "' . $requestBody->data->qrType . '",
                    "source"                : "' . $requestBody->data->source . '",
                    "supportType"           : "' . $requestBody->data->supportType . '",
                    "tokenType"             : "' . $requestBody->data->tokenType . '",
                    "trips"                 : "' . $requestBody->data->trips . '"
                },
                "payment": {
                    "pass_price"            : "' . $requestBody->payment->pass_price . '",
                    "pgId"                  : "' . $requestBody->payment->pgId . '"
                }
            }',
            CURLOPT_HTTPHEADER => [
                "Authorization:  $AUTHORIZATION",
                'Content-Type:  application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $Response = json_decode($response);

        if ($Response->status == "OK") {

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
            $newMaster->travel_date = date('y-m-d h:i:m', $Response->data->travelDate);
            $newMaster->master_expiry = date('y-m-d h:i:m', $Response->data->masterExpiry);
            $newMaster->grace_expiry = date('y-m-d h:i:m', $Response->data->graceExpiry);
            $newMaster->record_date = date('y-m-d h:i:m', $Response->data->timestamp);

            $newMaster->save();

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
                $Qr->record_date = date('y-m-d h:i:m', $trip->issueTime);
                $Qr->slave_expiry_date = date('y-m-d h:i:m', $trip->expiryTime);

                $Qr->save();

            }

            $newResponse = json_decode($response, true);
            $newResponse['order_no'] = $requestBody->data->operatorTransactionId;
            return json_encode($newResponse);

        } else if ($Response->status == "BSE") {

            return $response;
        }

        return json_encode([
            "status" => "failed",
            "message" => "Can't generate QR code!",
            "error" => "Internal server error!"],
            JSON_PRETTY_PRINT);
    }
}
