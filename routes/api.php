<?php

use Illuminate\Http\Request;
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
Route::post('/login', [\App\Http\Controllers\AgentController::class,'login']);

Route::get('client', [\App\Http\Controllers\ClientController::class, 'index']);
Route::get('client/{id}', [\App\Http\Controllers\ClientController::class, 'show']);
Route::post('client', [\App\Http\Controllers\ClientController::class, 'store']);
Route::put('client/{id}', [\App\Http\Controllers\ClientController::class, 'update']);
Route::delete('client/{id}', [\App\Http\Controllers\ClientController::class, 'destroy']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::resource('/agent/notification', \App\Http\Controllers\AgentController::class)->only(['store']);
    Route::get('/agent/notification/{id}', [\App\Http\Controllers\AgentController::class, 'getNotification']);
    Route::get('/agent/filterNotification', [\App\Http\Controllers\AgentController::class, 'getNotificationsWithFilter']);
    Route::get('/agent/client/{id}', [\App\Http\Controllers\AgentController::class, 'getClient']);
    Route::get('/agent/allClients', [\App\Http\Controllers\AgentController::class, 'getAllClients']);
});
