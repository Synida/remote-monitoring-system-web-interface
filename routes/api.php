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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//// access to login function
//Route::post('/login', 'AuthController@login');
//
//Route::get('/measurable', 'MeasurableController@index');
//Route::get('/measurable/active-measurable', 'MeasurableController@getActiveMeasurable');
//Route::options('/login', 'AuthController@options');

//Route::group(['middleware' => 'auth:api'], function() {
//    // logging out a user
//    Route::post('/logout', 'AuthController@logoutView');
//    // get multiple measurable objects from the db for display
//    Route::get('/measurable', 'MeasurableController@actionIndex');
//    // get the last measurable object for display
//    Route::get('/measurable/last', 'MeasurableController@actionGetLast');
//});
