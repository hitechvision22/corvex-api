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

Route::controller(App\Http\Controllers\Api\AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('loginAdmin', 'loginAdmin');
    Route::post('sendmail', 'sendmail');
    Route::post('send-code', 'send');
    Route::post('valitated', 'valitated');
    Route::post('resetpassword/{id}', 'resetpassword');
});



