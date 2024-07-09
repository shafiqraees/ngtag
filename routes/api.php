<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

Route::group(['prefix' => 'oauth', 'middleware' => ['api']], function () {
    Route::post('token', [AccessTokenController::class, 'issueToken']);
    Route::get('authorize', [AuthorizationController::class, 'authorize']);
    Route::post('token/refresh', [TransientTokenController::class, 'refresh']);
    Route::get('tokens', [AccessTokenController::class, 'forUser']);
    Route::delete('tokens/{token_id}', [AccessTokenController::class, 'destroy']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients', [ClientController::class, 'forUser']);
    Route::put('clients/{client_id}', [ClientController::class, 'update']);
    Route::delete('clients/{client_id}', [ClientController::class, 'destroy']);
    Route::get('scopes', [ScopeController::class, 'all']);
    Route::get('personal-access-tokens', [PersonalAccessTokenController::class, 'forUser']);
    Route::post('personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
    Route::delete('personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);
});

