<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorpTagListController;
use App\Http\Controllers\OtpProcessController;
use App\Http\Controllers\CorpCustomerAccountController;
use App\Http\Controllers\AuthController;



Route::middleware([/*'auth:token',*/'throttle:10,1'])->group(function () {
    Route::apiResources([
        'corptaglist' => CorpTagListController::class,
    ]);
    Route::post('generateotp', [OtpProcessController::class, 'store']);
    Route::post('verifyotp', [OtpProcessController::class, 'verifyOtp']);
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
    Route::middleware('auth:corp_customer_accounts')->group(function (){
        Route::put('updatecustomer/{customer_account_id}', [CorpCustomerAccountController::class, 'update']);
        Route::post('buy/tags', [CorpCustomerAccountController::class, 'buyReserveTags']);
    });

});
