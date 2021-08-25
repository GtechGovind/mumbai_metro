<?php

namespace App\Http\Controllers;

use App\Models\Stations;

class StationController extends Controller
{
    public function getAllStations()
    {
        return Stations::all();
    }

    public function getStation($id)
    {
        return Stations::all()->find($id);
    }
}
