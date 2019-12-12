<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('teste')->group(function () {
    # BinanceController
    Route::get('usdc-wallet', 'BinanceController@getUsdcWallet');
    Route::get('btc-wallet', 'BinanceController@getBtcWallet');
    Route::get('conversor', 'BinanceController@conversor');
    Route::get('exchanges', 'BinanceController@index');
});
