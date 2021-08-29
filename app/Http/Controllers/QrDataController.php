<?php

namespace App\Http\Controllers;

use App\Models\QrData;
use PDOException;

class QrDataController extends Controller
{
    public function populateQrData(QrData $qrData): bool
    {
        try {
            $qrData -> save();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
