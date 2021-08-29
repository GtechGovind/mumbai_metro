<?php

namespace App\Http\Controllers;

use App\Models\ApiModels\Data;
use App\Models\ApiModels\issueTokenModel;
use App\Models\ApiModels\Payment;
use App\Models\Master;
use Illuminate\Http\Request;

class GenerateQrController extends Controller
{
    public function GenerateQrCode(Request $request) {

        $data = new Data(
            $activationTime = $request -> input('data') -> activationTime,
            $destination = $request -> input('data') -> destination,
            $email = $request -> input('data') -> email,
            $fare = $request -> input('data') -> fare,
            $mobile = $request -> input('data') -> mobile,
            $name = $request -> input('data') -> name,
            $operationTypeId = $request -> input('data') -> operationTypeId,
            $operatorId = $request -> input('data') -> operatorId,
            $operatorTransactionId = $request -> input('data') -> operatorTransactionId,
            $qrType = $request -> input('data') -> qrType,
            $source = $request -> input('data') -> source,
            $supportType = $request -> input('data') -> supportType,
            $tokenType = $request -> input('data') -> tokenType,
            $trips = $request -> input('data') -> trips,
        );

        $payment = new Payment(
            $pass_price = $request -> input('payment') -> pass_price,
            $pgId = $request -> input('payment') -> pgId
        );

        $issueTokenModel = new issueTokenModel(
            $data,
            $payment
        );

        $BASE_URL = env("MMOPL_BASE_API_URL");
        $AUTHORIZATION = env("MMOPL_BASE_AUTH_KEY");

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
            CURLOPT_POSTFIELDS => $issueTokenModel,
            CURLOPT_HTTPHEADER => [
                "Authorization:  $AUTHORIZATION",
                'Content-Type:  application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $this->populateMaster()

        return $response;
    }

    private function populateMaster(Request $request) {

        $request->validate([
            'order_no' => 'required',
            'master_qr_code' => 'required',
            'master_tnx_id' => 'required',
            'phone_number' => 'required',
            'source' => 'required',
            'destination' => 'required',
            'ticket_type' => 'required',
            'ticket_count' => 'required',
            'total_fare' => 'required',
            'travel_date' => 'required',
            'master_expiry' => 'required',
            'grace_expiry' => 'required',
            'record_date' => 'required',
        ]);

        $order_no = $request -> input('order_no');
        $master_qr_code = $request -> input('master_qr_code');
        $master_tnx_id = $request -> input('master_tnx_id');
        $phone_number = $request -> input('phone_number');
        $source = $request -> input('source');
        $destination = $request -> input('destination');
        $ticket_type = $request -> input('ticket_type');
        $ticket_count = $request -> input('ticket_count');
        $total_fare = $request -> input('total_fare');
        $travel_date = $request -> input('travel_date');
        $master_expiry = $request -> input('master_expiry');
        $grace_expiry = $request -> input('grace_expiry');
        $record_date = $request -> input('record_date');

        $newMaster = new Master();

        $newMaster -> order_no = $order_no;
        $newMaster -> master_qr_code = $master_qr_code;
        $newMaster -> master_tnx_id = $master_tnx_id;
        $newMaster -> phone_number = $phone_number;
        $newMaster -> source = $source;
        $newMaster -> destination = $destination;
        $newMaster -> ticket_type = $ticket_type;
        $newMaster -> ticket_count = $ticket_count;
        $newMaster -> total_fare = $total_fare;
        $newMaster -> travel_date = $travel_date;
        $newMaster -> master_expiry = $master_expiry;
        $newMaster -> grace_expiry = $grace_expiry;
        $newMaster -> record_date = $record_date;

        $newMaster -> save();



        return json_encode([
            "status" => true,
            "message" => "created new master record",
            "data" => $newMaster
        ], JSON_PRETTY_PRINT);

    }
}
