<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Artisan;

Route::get('/', function (){
    return "<h1> Olá =D <h1>";
});

Route::get('/cron', function (){
    Artisan::call('schedule:run');
    return "EXEC:" .date("H:i". "\n");
});

// ACCOUNT
Route::group(['prefix' => 'api/account'], function(){
    Route::post('register', 'AccountController@register');
    Route::post('login', 'AccountController@login');
});


// ACCOUNT JWT
Route::group([ 'middleware' => ['auth'], 'prefix' => 'api/account'], function(){
    Route::post('deposit', 'AccountController@deposit');
    Route::get('balance', 'AccountController@balance');
});

// CRYPTO JWT
Route::group([ 'middleware' => ['auth'], 'prefix' => 'api/crypto/btc'], function(){
    Route::get('price', 'CryptoController@price');
    Route::post('purchase', 'CryptoController@purchase');
    Route::get('position', 'CryptoController@position');
    Route::post('sales/order', 'CryptoController@sales_order');
    Route::get('volume', 'CryptoController@volume');
    Route::get('history', 'CryptoController@history');
});

// EXTRACT JWT
Route::group([ 'middleware' => ['auth'], 'prefix' => 'api/extract'], function(){
    Route::get('/', 'ExtractController@extract');
    Route::get('period/{start}/{end}', 'ExtractController@extract_period');
});
