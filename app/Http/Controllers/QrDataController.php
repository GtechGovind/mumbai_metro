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
        return QrData::all()->where('order_no', '=', $order_no)  -> toJson();

    }
}
