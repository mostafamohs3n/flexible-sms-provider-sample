<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('sms.create');
});

Route::fallback(function (){
    return redirect()->route('sms.create');
});


Route::resource('/sms', SMSController::class)->only(['create', 'store']);

Route::get('/otp', [OtpController::class, 'store']);
