<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrDataController extends Controller
{
    public function getQrData(Request $request)
    {
        $request->validate([
            'order_no' => 'required',
        ]);

        $order_no = $request->input('order_no');

        $qrs = DB::table('qr_data')
            ->where('order_no', '=', $order_no)
            ->get();

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
