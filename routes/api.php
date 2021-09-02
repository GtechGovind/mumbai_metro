<?php

use App\Http\Controllers\FareController;
use App\Http\Controllers\GeneratePassController;
use App\Http\Controllers\GenerateQrController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QrDataController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// STATIONS
Route::get('stations', [StationController::class, 'getAllStations']);
Route::get('stations/{id}', [StationController::class, 'getStation']);

// CREATE QR
Route::post('qr/generate', [GenerateQrController::class, 'GenerateQrCode']);

// FARE
Route::post('fare', [FareController::class, 'getFare']);

// ORDER
Route::post('order/add', [OrderController::class, 'createOder']);

// SHOW OR
Route::post('qr', [QrDataController::class, 'getQrData']);

// CREATE PASS
Route::post('pass/generate', [GeneratePassController::class, 'generatePass']);

//USER
Route::get('users', [UserController::class, 'getAllUsers']);
Route::post('user', [UserController::class, 'getUser']);
Route::post('user/add', [UserController::class, 'addUser']);
