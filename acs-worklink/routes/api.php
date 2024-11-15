<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormCreateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create', [FormCreateController::class, 'create']);
Route::get('/jobs', [FormCreateController::class, 'jobs']);
Route::get('/job/{id}', [FormCreateController::class, 'job']);