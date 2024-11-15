<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\JobScheduleController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\JobController;

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

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/job-status', [JobStatusController::class, 'fetchJobStatuses']);
Route::get('/count-completed-schedules', [JobScheduleController::class, 'countCompletedSchedules']);

Route::get('/work-shift', [JobScheduleController::class, 'fetchWorkShifts']);
Route::post('/work-shift-byuser', [JobScheduleController::class, 'fetchWorkShiftsByUser']);
Route::get('/zone', [JobScheduleController::class, 'fetchZones']);
Route::post('/zone-byuser', [JobScheduleController::class, 'fetchZonesByUser']);
Route::post('/job-schedule', [JobScheduleController::class, 'fetchJobSchedules']);
Route::post('/count-checked-points', [JobScheduleController::class, 'fetchCheckedPointsCount']);
Route::post('/save-inspection-result', [JobScheduleController::class, 'saveInspectionResult']);
Route::post('/location-details', [JobScheduleController::class, 'fetchLocationDetails']);
Route::post('/images-job', [JobScheduleController::class, 'fetchImagesJobschedules']);
Route::post('/job-history', [JobScheduleController::class, 'fetchJobSchedulesHistory']);
Route::get('/issue-topic', [JobScheduleController::class, 'fetchIssueTopics']);

Route::post('/check-location', [LocationController::class, 'checkLocation']);