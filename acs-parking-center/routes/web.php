<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberTypeController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\CompanyStatusController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingRecordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'showLogin'])->name('showLogin');
Route::post('/login/loginPerform', [LoginController::class, 'loginPerform'])->name('loginPerform');


Route::group(['middleware' => ['auth']], function() {
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('showRegister');
    Route::post('/register/registerPerform', [RegisterController::class, 'registerPerform'])->name('registerPerform');
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/car', [CarController::class, 'carDashboard'])->name('carDashboard');
    Route::get('/car/in', [CarController::class, 'carIn'])->name('carIN');
    Route::get('/car/out', [CarController::class, 'carOut'])->name('carOUT');
    Route::get('/car/recordParking', [CarController::class, 'recordParking'])->name('recordParking');
    
    Route::get('/members', [MemberController::class, 'index'])->name('memberList');
    Route::get('/members/getMembers', [MemberController::class, 'getMembers'])->name('getMembers');
    Route::get('/members/getMemberById/{id}', [MemberController::class, 'getMemberById'])->name('getMemberById');
    Route::post('/members/insertMember', [MemberController::class, 'insertMember'])->name('insertMember');
    Route::post('/members/updateMember/{id}', [MemberController::class, 'updateMember'])->name('updateMember');
    Route::delete('/members/deleteMember/{id}', [MemberController::class, 'deleteMember'])->name('deleteMember');
    Route::get('/report/members/exportMemberList', [MemberController::class, 'exportMemberList'])->name('exportMemberList');
    
    Route::get('/memberTypes', [MemberTypeController::class, 'index'])->name('memberTypes');
    Route::get('/memberTypes/getMemberTypes', [MemberTypeController::class, 'getMemberTypes'])->name('getMemberTypes');
    Route::get('/memberTypes/getMemberTypeById/{id}', [MemberTypeController::class, 'getMemberTypeById'])->name('getMemberTypeById');
    Route::post('/memberTypes/insertMemberType', [MemberTypeController::class, 'insertMemberType'])->name('insertMemberType');
    Route::post('/memberTypes/updateMemberType/{id}', [MemberTypeController::class, 'updateMemberType'])->name('updateMemberType');
    Route::delete('/memberTypes/deleteMemberType/{id}', [MemberTypeController::class, 'deleteMemberType'])->name('deleteMemberType');
    Route::post('/toggleMemberStatus', [MemberController::class, 'toggleMemberStatus'])->name('toggleMemberStatus');
    
    Route::get('/places', [PlaceController::class, 'index'])->name('placeList');
    Route::get('/places/getPlaces', [PlaceController::class, 'getPlaces'])->name('getPlaces');
    Route::get('/places/getPlaceById/{id}', [PlaceController::class, 'getPlaceById'])->name('getPlaceById');
    Route::post('/places/insertPlace', [PlaceController::class, 'insertPlace'])->name('insertPlace');
    Route::post('/places/updatePlace/{id}', [PlaceController::class, 'updatePlace'])->name('updatePlace');
    Route::delete('/places/deletePlace/{id}', [PlaceController::class, 'deletePlace'])->name('deletePlace');
    
    Route::get('/floors', [FloorController::class, 'index'])->name('floors');
    Route::get('/floors/getFloors', [FloorController::class, 'getFloors'])->name('getFloors');
    Route::get('/floors/getFloorById/{id}', [FloorController::class, 'getFloorById'])->name('getFloorById');
    Route::post('/floors/insertFloor', [FloorController::class, 'insertFloor'])->name('insertFloor');
    Route::post('/floors/updateFloor/{id}', [FloorController::class, 'updateFloor'])->name('updateFloor');
    Route::delete('/floors/deleteFloor/{id}', [FloorController::class, 'deleteFloor'])->name('deleteFloor');
    
    Route::get('/companyStatus', [CompanyStatusController::class, 'index'])->name('companyStatus');
    Route::get('/companyStatus/getCompanyStatus', [CompanyStatusController::class, 'getCompanyStatus'])->name('getCompanyStatus');
    Route::get('/companyStatus/getCompanyStatusById/{id}', [CompanyStatusController::class, 'getCompanyStatusById'])->name('getCompanyStatusById');
    Route::post('/companyStatus/insertCompanyStatus', [CompanyStatusController::class, 'insertCompanyStatus'])->name('insertCompanyStatus');
    Route::post('/companyStatus/updateCompanyStatus/{id}', [CompanyStatusController::class, 'updateCompanyStatus'])->name('updateCompanyStatus');
    Route::delete('/companyStatus/deleteCompanyStatus/{id}', [CompanyStatusController::class, 'deleteCompanyStatus'])->name('deleteCompanyStatus');
    
    Route::get('/stamps', [StampController::class, 'index'])->name('stamps');
    Route::get('/stamps/getStamps', [StampController::class, 'getStamps'])->name('getStamps');
    Route::get('/stamps/getStampById/{id}', [StampController::class, 'getStampById'])->name('getStampById');
    Route::post('/stamps/insertStamp', [StampController::class, 'insertStamp'])->name('insertStamp');
    Route::post('/stamps/updateStamp/{id}', [StampController::class, 'updateStamp'])->name('updateStamp');
    Route::delete('/stamps/deleteStamp/{id}', [StampController::class, 'deleteStamp'])->name('deleteStamp');
    
    Route::get('/companies', [CompanyController::class, 'index'])->name('companyList');
    Route::get('/companies/getCompanies', [CompanyController::class, 'getCompanies'])->name('getCompanies');
    Route::get('/companies/getCompanyById/{id}', [CompanyController::class, 'getCompanyById'])->name('getCompanyById');
    Route::post('/companies/insertCompany', [CompanyController::class, 'insertCompany'])->name('insertCompany');
    Route::post('/companies/updateCompany/{id}', [CompanyController::class, 'updateCompany'])->name('updateCompany');
    Route::delete('/companies/deleteCompany/{id}', [CompanyController::class, 'deleteCompany'])->name('deleteCompany');
    
    Route::get('/companySetup', [CompanySetupController::class, 'index'])->name('companySetup');
    Route::get('/companySetup/getCompanySetup', [CompanySetupController::class, 'getCompanySetup'])->name('getCompanySetup');
    Route::get('/companySetup/getCompanySetupById/{id}', [CompanySetupController::class, 'getCompanySetupById'])->name('getCompanySetupById');
    Route::post('/companySetup/insertCompanySetup', [CompanySetupController::class, 'insertCompanySetup'])->name('insertCompanySetup');
    Route::post('/companySetup/updateCompanySetup/{id}', [CompanySetupController::class, 'updateCompanySetup'])->name('updateCompanySetup');
    Route::delete('/companySetup/deleteCompanySetup{id}', [CompanySetupController::class, 'deleteCompanySetup'])->name('deleteCompanySetup');
    Route::post('/companySetup/reQuotaCompany{id}', [CompanySetupController::class, 'reQuotaCompany'])->name('reQuotaCompany');
    
    Route::get('/search/companies', [SearchController::class, 'searchCompanies'])->name('searchCompanies');
    Route::get('/search/stamps', [SearchController::class, 'searchStamps'])->name('searchStamps');
    Route::get('/search/floors', [SearchController::class, 'searchFloors'])->name('searchFloors');
    Route::get('/search/places', [SearchController::class, 'searchPlaces'])->name('searchPlaces');
    
    Route::get('/paymentMethod', [PaymentMethodController::class, 'index'])->name('paymentMethod');
    Route::get('/paymentMethod/getPaymentMethods', [PaymentMethodController::class, 'getPaymentMethods'])->name('getPaymentMethods');
    
    
    // Reports
    Route::get('/report/monthlyStampSummary', [ReportController::class, 'monthlyStampSummaryList'])->name('monthlyStampSummaryList');
    Route::get('/report/monthlyStampSummary/getMonthlyStampSummary', [ReportController::class, 'getMonthlyStampSummaryReport'])->name('getMonthlyStampSummaryReport');
    Route::get('/report/monthlyStampSummary/exportMonthlyStampSummaryPDF', [ReportController::class, 'exportMonthlyStampSummaryPDF'])->name('exportMonthlyStampSummaryPDF');
    Route::post('/report/monthlyStampSummary/exportMonthlyStampSummaryExcel', [ReportController::class, 'exportMonthlyStampSummaryExcel'])->name('exportMonthlyStampSummaryExcel');

    Route::get('/report/dailyParkingFee', [ReportController::class, 'dailyParkingFeeList'])->name('dailyParkingFeeList');
    Route::get('/report/dailyParkingFee/getDailyParkingFee', [ReportController::class, 'getDailyParkingFeeReport'])->name('getDailyParkingFeeReport');
    Route::get('/report/dailyParkingFee/exportDailyParkingFee', [ReportController::class, 'exportDailyParkingFee'])->name('exportDailyParkingFee');

    Route::get('/report/test', [ReportController::class, 'test'])->name('test');

    Route::get('/recordParking', [ParkingRecordController::class, 'recordParking'])->name('recordParking');
    Route::get('/recordParking/getRecordParkings', [ParkingRecordController::class, 'getRecordParkings'])->name('getRecordParkings');
    Route::post('/recordParking/insertRecordParking', [ParkingRecordController::class, 'insertRecordParking'])->name('insertRecordParking');
    Route::get('/recordParking/getParkingRecordById/{id}', [ParkingRecordController::class, 'getParkingRecordById'])->name('getParkingRecordById');
    Route::post('/recordParking/updateRecordParking/{id}', [ParkingRecordController::class, 'updateRecordParking'])->name('updateRecordParking');
    Route::get('/historyRecordManual', [ParkingRecordController::class, 'historyRecordManual'])->name('historyRecordManual');
    Route::get('/recordParking/getHistoryRecordManual', [ParkingRecordController::class, 'getHistoryRecordManual'])->name('getHistoryRecordManual');
});





