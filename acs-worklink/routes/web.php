<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\WorkModeController;
use App\Http\Controllers\JobQualificationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('postLogin');


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/users', [AuthController::class, 'users'])->name('users');
    Route::get('/users/getUsers', [AuthController::class, 'getUsers'])->name('getUsers');
    Route::get('/users/getUserById/{id}', [AuthController::class, 'getUserById'])->name('getUserById');
    Route::post('/users/insertUser', [AuthController::class, 'insertUser'])->name('insertUser');
    Route::post('/users/updateUser/{id}', [AuthController::class, 'updateUser'])->name('updateUser');
    Route::delete('/users/deleteUser/{id}', [AuthController::class, 'deleteUser'])->name('deleteUser');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/emails/inbox', [EmailController::class, 'index'])->name('inbox');
    Route::get('/emails/read/{id}', [EmailController::class, 'read'])->name('read');
    Route::get('/emails/compose', function(){ return view('email.compose'); })->name('compose');
    
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::get('/departments/getDepartments', [DepartmentController::class, 'getDepartments'])->name('getDepartments');
    Route::get('/departments/getDepartmentById/{id}', [DepartmentController::class, 'getDepartmentById'])->name('getDepartmentById');
    Route::post('/departments/insertDepartment', [DepartmentController::class, 'insertDepartment'])->name('insertDepartment');
    Route::post('/departments/updateDepartment/{id}', [DepartmentController::class, 'updateDepartment'])->name('updateDepartment');
    Route::delete('/departments/deleteDepartment/{id}', [DepartmentController::class, 'deleteDepartment'])->name('deleteDepartment');
    
    Route::get('/workModes', [WorkModeController::class, 'index'])->name('workModes');
    Route::get('/workModes/getWorkModes', [WorkModeController::class, 'getWorkModes'])->name('getWorkModes');
    Route::get('/workModes/getWorkModeById/{id}', [WorkModeController::class, 'getWorkModeById'])->name('getWorkModeById');
    Route::post('/workModes/insertWorkMode', [WorkModeController::class, 'insertWorkMode'])->name('insertWorkMode');
    Route::post('/workModes/updateWorkMode/{id}', [WorkModeController::class, 'updateWorkMode'])->name('updateWorkMode');
    Route::delete('/workModes/deleteWorkMode/{id}', [WorkModeController::class, 'deleteWorkMode'])->name('deleteWorkMode');
    
    Route::get('/jobQualifications', [JobQualificationController::class, 'index'])->name('jobQualifications');
    Route::get('/jobQualifications/getJobQualifications', [JobQualificationController::class, 'getJobQualifications'])->name('getJobQualifications');
    Route::get('/jobQualifications/getJobQualificationById/{id}', [JobQualificationController::class, 'getJobQualificationById'])->name('getJobQualificationById');
    Route::post('/jobQualifications/insertJobQualification', [JobQualificationController::class, 'insertJobQualification'])->name('insertJobQualification');
    Route::post('/jobQualifications/updateJobQualification/{id}', [JobQualificationController::class, 'updateJobQualification'])->name('updateJobQualification');
    Route::delete('/jobQualifications/deleteJobQualification/{id}', [JobQualificationController::class, 'deleteJobQualification'])->name('deleteJobQualification');
    
    Route::get('/positions', [PositionController::class, 'index'])->name('positions');
    Route::get('/positions/getPositions', [PositionController::class, 'getPositions'])->name('getPositions');
    Route::get('/positions/getPositionById/{id}', [PositionController::class, 'getPositionById'])->name('getPositionById');
    Route::post('/positions/insertPosition', [PositionController::class, 'insertPosition'])->name('insertPosition');
    Route::post('/positions/updatePosition/{id}', [PositionController::class, 'updatePosition'])->name('updatePosition');
    Route::delete('/positions/deletePosition/{id}', [PositionController::class, 'deletePosition'])->name('deletePosition');
});

