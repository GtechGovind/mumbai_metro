<?php

namespace App\Http\Controllers;

use App\Models\Fare;
use Illuminate\Http\Request;

class FareController extends Controller
{
    public function getFare(Request $request)
    {

        $request -> validate([
            'source' => 'required',
            'destination' => 'required',
            'ticket_type' => 'required',
            'ticket_count' => 'required'
        ]);

        $source = $request -> input('source');
        $destination = $request -> input('destination');
        $ticket_type = $request -> input('ticket_type');
        $ticket_count = (int) $request -> input('ticket_count');

        $fare =  Fare::all()  -> where('source', '=', $source)
                            -> where('destination', '=', $destination)
                            -> first();
        $ffst = $fare -> fare;

        if ($ticket_type == "Single") {

            return json_encode([
                "status" => true,
                "message" => "Fare for single journey ticket.",
                "fare" => [
                    "ffst" => $ffst,
                    "total_fare" => $ffst * $ticket_count
                ]
            ], JSON_PRETTY_PRINT);

        } else if ($ticket_type == "Return") {

            return json_encode([
                "status" => true,
                "message" => "Fare for return journey ticket.",
                "fare" => [
                    "ffst" => $ffst,
                    "total_fare" => 2 * $ffst * $ticket_count
                ]
            ], JSON_PRETTY_PRINT);

        } else {

            return json_encode([
                "status" => false,
                "message" => "Can't get fare",
                "error" => "Some internal server error"
            ], JSON_PRETTY_PRINT);

        }
    }
}
