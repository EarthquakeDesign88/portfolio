<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\JobScheduleController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SystemTriggerController;
use App\Http\Controllers\IssueTopicController;

Route::get('/login', [AccountController::class, 'login_template'])->name('login.template');
Route::post('/login', [AccountController::class, 'login'])->name('login');

Route::middleware(['login'])->group(function () {

    Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');

    //jobstatus
    Route::post('/jobstatus', [JobController::class, 'job_status_create'])->name('jobstatus.create');
    Route::get('/jobstatus/{id}', [JobController::class, 'job_status_get'])->name('jobstatus.get');
    Route::put('/jobstatus/{id}', [JobController::class, 'job_status_update'])->name('jobstatus.update');
    Route::get('/jobstatus', [JobController::class, 'job_status'])->name('jobstatus');

    //jobschedules
    Route::get('/jobschedules', [JobController::class, 'job_schedules'])->name('jobschedules');
    Route::get('/jobschedules/{id}', [JobController::class, 'job_schedules_image'])->name('jobschedule_image');

    //workshift
    Route::get('/workshift', [JobController::class, 'workshift'])->name('workshift');
    Route::get('/workshift/{id}', [JobController::class, 'workshift_get'])->name('workshift.get');
    Route::put('/workshift/status', [JobController::class, 'workshift_status'])->name('workshift.status');
    Route::put('/workshift/{id}', [JobController::class, 'workshift_update'])->name('workshift.update');
    Route::post('/workshift', [JobController::class, 'workshift_create'])->name('workshift.create');
    Route::put('/workshift', [JobController::class, 'workshift_del'])->name('workshift.del');

    //job authority
    Route::get('/authority', [JobController::class, 'authority'])->name('authority');
    Route::get('/authority/{id}', [JobController::class, 'authority_get'])->name('authority.get');
    Route::get('/authority/user/{id}', [JobController::class, 'authority_user'])->name('authority.user');
    Route::put('/authority/{id}', [JobController::class, 'authority_update'])->name('authority.update');
    Route::post('/authority', [JobController::class, 'authority_create'])->name('authority.create');

    //repairs
    Route::get('/repairs', [RepairController::class, 'repairs'])->name('repairs');

    //zone
    Route::get('/zones', [PlaceController::class, 'zones'])->name('zones');
    Route::get('/zone/{id}', [PlaceController::class, 'zone_get'])->name('zone.get');
    Route::put('/zone/status', [PlaceController::class, 'zone_status'])->name('zone.status');
    Route::put('/zone/{id}', [PlaceController::class, 'zone_update'])->name('zone.update');
    Route::post('/zone', [PlaceController::class, 'zone_create'])->name('zone.create');
    Route::put('/zone', [PlaceController::class, 'zone_del'])->name('zone.del');

    //location
    Route::get('/locations', [PlaceController::class, 'locations'])->name('locations');
    Route::get('/location/{id}', [PlaceController::class, 'location_get'])->name('location.get');
    Route::put('/location/{id}', [PlaceController::class, 'location_update'])->name('location.update');
    Route::put('/locations', [PlaceController::class, 'location_del'])->name('location.del');
    Route::put('/locations/status', [PlaceController::class, 'location_status'])->name('location.status');
    Route::post('/location', [PlaceController::class, 'location_create'])->name('location.create');
    Route::get('/report/location', [PlaceController::class, 'report_location'])->name('report.location');

    //account
    Route::get('/accounts', [AccountController::class, 'accounts'])->name('accounts');
    Route::get('/account/{id}', [AccountController::class, 'account_get'])->name('account.get');
    Route::put('/account/{id}', [AccountController::class, 'account_update'])->name('account.update');
    Route::post('/account', [AccountController::class, 'account_create'])->name('account.create');

    //role
    Route::get('/roles', [AccountController::class, 'roles'])->name('roles');
    Route::get('/role/{id}', [AccountController::class, 'role_get'])->name('role.get');
    Route::put('/role/{id}', [AccountController::class, 'role_update'])->name('role.update');
    Route::post('/role', [AccountController::class, 'role_create'])->name('role.create');


    Route::get('/bulkInsertAuthorities', [JobScheduleController::class, 'bulkInsertAuthorities']);
    Route::get('/bulkInsertLocations', [JobScheduleController::class, 'bulkInsertLocations']);
    Route::get('/bulkInsertJob', [JobScheduleController::class, 'bulkInsertJob']);

    Route::get('/jobschedules-trigger', [SystemTriggerController::class, 'jobschedulesTrigger']);
    Route::post('/jobschedules-trigger-run', [SystemTriggerController::class, 'JobschedulesTrigger_run'])->name('jobschedulesTrigger.run');

    Route::get('/issueTopics', [IssueTopicController::class, 'issueTopics'])->name('issueTopics');
    Route::get('/issueTopic/{id}', [IssueTopicController::class, 'issueTopic_get'])->name('issueTopic.get');
    Route::put('/issueTopic/{id}', [IssueTopicController::class, 'issueTopic_update'])->name('issueTopic.update');
    Route::post('/issueTopic', [IssueTopicController::class, 'issueTopic_create'])->name('issueTopic.create');
});
