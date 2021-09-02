<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\PassData;
use Illuminate\Http\Request;

class GeneratePassController extends Controller
{
    public function generatePass(Request $request)
    {
        $BASE_URL = env("MMOPL_BASE_API_URL");
        $AUTHORIZATION = env("MMOPL_BASE_AUTH_KEY");

        $requestBody = json_decode($request->getContent());

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL/qrcode/issuePass",
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
                    "trips"                 : "' . $requestBody->data->tokenType . '"
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

        $newPass = new PassData();

        $newPass->order_no = $requestBody->data->operatorTransactionId;
        $newPass->phone_number = $requestBody->data->mobile;
        $newPass->master_qr_code = $Response->masterTxnId;
        $newPass->acc_id = $Response->transactionId;
        $newPass->pass_price = $Response->amount;
        $newPass->balance = $Response->balance;
        $newPass->reg_fees = $Response->registrationFee;
        $newPass->trips = 0;
        $newPass->operator_id = $Response->operator_id;
        $newPass->travel_date = date('y-m-d h:i:m', $Response->data->travelDate);
        $newPass->master_expiry = date('y-m-d h:i:m', $Response->data->masterExpiry);
        $newPass->grace_expiry = date('y-m-d h:i:m', $Response->data->graceExpiry);

        $newPass->save();

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
