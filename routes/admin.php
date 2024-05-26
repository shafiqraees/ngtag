<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DocumentReviewController;


Route::middleware([/*'auth:token',*/'throttle:10,1'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'adminLogin']);
    });
    Route::middleware('auth:admin')->group(function (){

        Route::get('allcustomer', [DocumentReviewController::class, 'allCorporateCustomer']);
        Route::post('docprocess/{customer_account_id}', [DocumentReviewController::class, 'documentProcess']);
    });
});
