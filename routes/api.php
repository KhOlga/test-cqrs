<?php

use App\Http\Controllers\Commands\SendMoney;
use App\Http\Controllers\Queries\Transactions;
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



Route::group([ 'prefix' => 'users', 'as' => 'users.'], function () {
	// Routes below only for writing data
	Route::group([ 'prefix' => 'commands', 'as' => 'commands.', 'namespace' => 'Commands'], function () {
		Route::post('send-money', 'SendMoney')->name('send_money');
	});

	// Routes below only for reading data
	Route::group([ 'prefix' => 'queries', 'as' => 'queries.', 'namespace' => 'Queries'], function () {
		Route::get('transactions', 'Transactions')->name('transactions');
	});
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
