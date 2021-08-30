<?php

namespace App\Http\Controllers;

use App\Models\QrData;
use Illuminate\Http\Request;

class QrDataController extends Controller
{
    public function getQrData(Request $request)
    {
        $request->validate([
            'order_no' => 'required',
        ]);

        $order_no = $request->input('order_no');

        $qrs = QrData::all()->where('order_no', '=', $order_no);

        if (empty($qrs)) return json_encode([
            "status" => false,
            "message" => "Can't fetch qr codes!",
            "code" => 501,
            "error" => "No Qr to show"
        ], JSON_PRETTY_PRINT);

        else return json_encode([
            "status" => true,
            "message" => "Qr Codes fetched successfully.",
            "code" => 500,
            "qrs" => $qrs
        ], JSON_PRETTY_PRINT);

    }
}
