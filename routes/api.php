<?php

use App\Http\Controllers\FareController;
use App\Http\Controllers\GenerateQrController;
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

//USER
Route::get('users', [UserController::class, 'getAllUsers']);
Route::post('user', [UserController::class, 'getUser']);
Route::post('user/add', [UserController::class, 'addUser']);
