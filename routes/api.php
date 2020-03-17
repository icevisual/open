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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


// Route::match(['get','post'],'/startCaptcha', [
//     'as' => 'api_startCaptcha',
//     'uses' => 'Open\OpenController@startCaptcha'
// ]);


// Route::match(['get','post'],'/verifyLogin', [
//     'as' => 'api_verifyLogin',
//     'uses' => 'Open\OpenController@verifyLogin'
// ]);
Route::match(['get', 'post'], '/t1', 'Test\TestController@create');
Route::match(['get', 'post'], '/t2', 'Test\TestController@select');


Route::group(['prefix' => 'mqtt',], function () {

    Route::match(['get', 'post'], '/system/time', 'Mqtt\AuthController@systemTime');

    Route::match(['get', 'post'], '/system/upgrade', 'Mqtt\AuthController@upgrade');

    Route::match(['get', 'post'], '/superuser', 'Mqtt\AuthController@superuser');
    Route::match(['get', 'post'], '/auth', 'Mqtt\AuthController@auth');
    Route::match(['get', 'post'], '/acl', 'Mqtt\AuthController@acl');

    Route::match(['get', 'post'], '/deviceNameCheck', 'Mqtt\AuthController@deviceNameCheck');
    Route::match(['get', 'post'], '/bindDevice', 'Mqtt\AuthController@bindDevice');

    Route::match(['get', 'post'], '/listBindedDevices', 'Mqtt\AuthController@listBindedDevices');

    Route::match(['get', 'post'], '/unbindDevice', 'Mqtt\AuthController@unbindDevice');

    Route::match(['post'], '/errorReport', 'Mqtt\AuthController@errorReport');

//     Route::match(['post'],'/jsErrorReport','Mqtt\AuthController@jsErrorReport');

//     Route::match(['get'],'/showJsErrorReport','Mqtt\AuthController@showJsErrorReport');


});














