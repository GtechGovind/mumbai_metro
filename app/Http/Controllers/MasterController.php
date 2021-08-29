<?php

namespace App\Http\Controllers;

use App\Models\Master;
use PDOException;

class MasterController extends Controller
{
    public function populateMasterTable(Master $master): bool
    {
        try {
            $master -> save();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
