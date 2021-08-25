<?php

use App\Http\Controllers\FareController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// STATIONS
Route::get('stations', [StationController::class, 'getAllStations']);
Route::get('stations/{id}', [StationController::class, 'getStation']);

// FARE
Route::post('fare', [FareController::class, 'getFare']);

//USER
Route::get('users', [UserController::class, 'getAllUsers']);
Route::post('user', [UserController::class, 'getUser']);
Route::post('add_user', [UserController::class, 'addUser']);
