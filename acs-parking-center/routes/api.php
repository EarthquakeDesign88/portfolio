<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ParkingRecordController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\auth\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/members', [MemberController::class, 'getMembersAPI']);
Route::post('/save', [MemberController::class, 'SaveData']);

Route::get('/verifyMemberCode/{memberCode}', [MemberController::class, 'verifyMemberCode']);

Route::get('/parking_records', [ParkingRecordController::class, 'index']);
Route::get('/parking_records/out', [ParkingRecordController::class, 'getParkingOut']);
Route::post('/parking_record/out', [ParkingRecordController::class, 'search']);
Route::post('/parking_record/in', [ParkingRecordController::class, 'insert']);

Route::post('/payment', [PaymentTransactionController::class, 'insert']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/insert/server', [ParkingRecordController::class, 'insertServer']);