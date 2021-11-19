<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mpesaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('get-token', [mpesaController::class,'getAccessToken']);
Route::post('register-urls', [mpesaController::class,'registerURLS']);
Route::post('simulate', [mpesaController::class,'simulateTransaction']);
Route::post('stkpush', [mpesaController::class, 'stkPush']);

Route::get('stk', function(){
    return view('stk');
});