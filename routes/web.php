<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResultController;

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


Route::controller(OrderController::class)->group(function(){
    Route::post('/order-create', 'store');
});

Route::controller(ResultController::class)->group(function(){
    Route::get('/results', 'index');
    Route::post('/results', 'store');
    Route::get('/current-game', 'getCurrentGame');
});