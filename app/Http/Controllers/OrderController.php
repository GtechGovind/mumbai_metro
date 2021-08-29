<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDOException;

class OrderController extends Controller
{
    public function createOder(Request $request) {

        $request->validate([
            'order_no' => 'required',
            'phone_number' => 'required',
            'pg_order_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'ticket_count' => 'required',
            'ticket_type' => 'required',
            'total_fare' => 'required',
            'pg_id' => 'required',
            'order_status' => 'required',
            'order_flag' => 'required',
        ]);

        $order_no = $request -> input('order_no');
        $phone_number = $request -> input('phone_number');
        $pg_order_id = $request -> input('pg_order_id');
        $source_id = $request -> input('source_id');
        $destination_id = $request -> input('destination_id');
        $ticket_count = $request -> input('ticket_count');
        $ticket_type = $request -> input('ticket_type');
        $total_fare = $request -> input('total_fare');
        $pg_id = $request -> input('pg_id');
        $order_status = $request -> input('order_status');
        $order_flag = $request -> input('order_flag');

        $newOrder = new Order();

        $newOrder -> order_no = $order_no;
        $newOrder -> phone_number = $phone_number;
        $newOrder -> pg_order_id = $pg_order_id;
        $newOrder -> source_id = $source_id;
        $newOrder -> destination_id = $destination_id;
        $newOrder -> ticket_count = $ticket_count;
        $newOrder -> ticket_type = $ticket_type;
        $newOrder -> total_fare = $total_fare;
        $newOrder -> pg_id = $pg_id;
        $newOrder -> order_status = $order_status;
        $newOrder -> order_flag = $order_flag;



        try {

            $newOrder -> save();
            return json_encode([
                "status" => true,
                "message" => "Order created",
                "order" => $newOrder
            ], JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            return json_encode([
                "status" => false,
                "message" => "Order creation failed",
                "order" => $e -> getMessage()
            ], JSON_PRETTY_PRINT);

        }

    }
}
