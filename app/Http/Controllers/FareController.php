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
            'destination' => 'required'
        ]);

        $source = $request -> input('source');
        $destination = $request -> input('destination');

        return Fare::all() ->where('source', '=', $source)
                            ->where('destination', '=', $destination)
                            ->first();
    }
}
