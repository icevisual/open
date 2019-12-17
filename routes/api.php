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


// Route::match(['get','post'],'/startCaptcha', [
//     'as' => 'api_startCaptcha',
//     'uses' => 'Open\OpenController@startCaptcha'
// ]);


// Route::match(['get','post'],'/verifyLogin', [
//     'as' => 'api_verifyLogin',
//     'uses' => 'Open\OpenController@verifyLogin'
// ]);


Route::match(['get', 'post'], '/captcha', [
    'as' => 'api_captcha',
    'uses' => 'Open\OpenController@captcha'
]);

Route::match(['get', 'post'], '/register', [
    'as' => 'api_register',
    'uses' => 'Open\OpenController@register'
]);

Route::match(['get', 'post'], '/sendRegisterEmail', [
    'as' => 'api_sendRegisterEmail',
    'uses' => 'Open\OpenController@sendRegisterEmail'
]);

Route::match(['get', 'post'], '/validateEmail', [
    'as' => 'api_validateEmail',
    'uses' => 'Open\OpenController@validateEmail'
]);

Route::match(['get', 'post'], '/emailHomeAddr', [
    'as' => 'api_emailHomeAddr',
    'uses' => 'Open\OpenController@emailHomeAddr'
]);

Route::match(['get', 'post'], '/region', [
    'as' => 'region',
    'uses' => 'Open\OpenController@region'
]);

Route::match(['get', 'post'], '/sendForgetEmail', [
    'as' => 'api_sendForgetEmail',
    'uses' => 'Open\OpenController@sendForgetEmail'
]);

Route::match(['get', 'post'], '/validateForgetEmail', [
    'as' => 'api_validateForgetEmail',
    'uses' => 'Open\OpenController@validateForgetEmail'
]);

Route::match(['get', 'post'], '/resetForgottenPasswd', [
    'as' => 'api_resetForgottenPasswd',
    'uses' => 'Open\OpenController@resetForgottenPasswd'
]);

Route::match(['get', 'post'], '/login', [
    'as' => 'api_login',
    'uses' => 'Open\LoginController@login'
]);
Route::match(['get', 'post'], '/logout', [
    'as' => 'api_logout',
    'uses' => 'Open\LoginController@logout'
]);

Route::group([
    'prefix' => '/developer'
], function () {

    Route::match(['get', 'post'], '/resetPassword', [
        'as' => 'api_password',
        'uses' => 'Open\LoginController@resetPassword'
    ]);

    Route::match(['get', 'post'], '/sendApplySms', [
        'as' => 'api_sendApplySms',
        'uses' => 'Open\LoginController@sendRegisterSms'
    ]);

    Route::match(['get', 'post'], '/applyDeveloper', [
        'as' => 'api_applyDeveloper',
        'uses' => 'Open\LoginController@applyDeveloper'
    ]);

    Route::match(['get', 'post'], '/accessKeyList', [
        'as' => 'api_accessKeyList',
        'uses' => 'Open\LoginController@accessKeyList'
    ]);

    Route::match(['get', 'post'], '/createAccessKey', [
        'as' => 'api_createAccessKey',
        'uses' => 'Open\LoginController@createAccessKey'
    ]);

    Route::match(['get', 'post'], '/disableAccessKey', [
        'as' => 'api_disableAccessKey',
        'uses' => 'Open\LoginController@disableAccessKey'
    ]);

    Route::match(['get', 'post'], '/enableAccessKey', [
        'as' => 'api_enableAccessKey',
        'uses' => 'Open\LoginController@enableAccessKey'
    ]);

    Route::match(['get', 'post'], '/deleteAccessKey', [
        'as' => 'api_deleteAccessKey',
        'uses' => 'Open\LoginController@deleteAccessKey'
    ]);

    Route::match(['get', 'post'], '/showSecretKey', [
        'as' => 'api_showSecretKey',
        'uses' => 'Open\LoginController@showSecretKey'
    ]);

    Route::match(['get', 'post'], '/applyShowSecretKeyToken', [
        'as' => 'api_applyShowSecretKeyToken',
        'uses' => 'Open\LoginController@applyShowSecretKeyToken'
    ]);


});


Route::group(['prefix' => 'back',], function () use ($router) {

    Route::match(['get', 'post'], '/developerApplyReviewList', [
        'as' => 'api_developerApplyReviewList',
        'uses' => 'Open\OpenController@developerApplyReviewList'
    ]);
    Route::match(['get', 'post'], '/developerApplyReview', [
        'as' => 'api_developerApplyReview',
        'uses' => 'Open\OpenController@developerApplyReview'
    ]);

});

Route::group(['prefix' => 'mqtt',], function () use ($router) {

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














