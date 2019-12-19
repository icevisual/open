<?php

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
//
//Route::get('/', function () {
//    return view('welcome');
//});


Route::group([
    'prefix' => 'api'
], function () {


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


    Route::group(['prefix' => 'back',], function () {

        Route::match(['get', 'post'], '/developerApplyReviewList', [
            'as' => 'api_developerApplyReviewList',
            'uses' => 'Open\OpenController@developerApplyReviewList'
        ]);
        Route::match(['get', 'post'], '/developerApplyReview', [
            'as' => 'api_developerApplyReview',
            'uses' => 'Open\OpenController@developerApplyReview'
        ]);

    });



});



Route::get('/404', [
    'as' => '404',
    'uses' => 'OpenWeb\IndexController@error_404'
]);


Route::get('/LHLH/controller', [
    'as' => 'get_LHLH_controller',
    'uses' => 'Mqtt\AuthController@LHLHController'
]);

Route::get('/LHLH/login', [
    'as' => 'get_LHLH_login',
    'uses' => 'Mqtt\AuthController@LHLHLoginPage'
]);


Route::get('/LHLH/simple/controller', [
    'as' => 'get_LHLH_simple_controller',
    'uses' => 'Mqtt\AuthController@LHLHSimpleController'
]);

Route::get('/LHLH/simple/login', [
    'as' => 'get_LHLH_simple_login',
    'uses' => 'Mqtt\AuthController@LHLHSimpleLoginPage'
]);


Route::post('/LHLH/Simple/Login', [
    'as' => 'post_LHLH_Simple_login',
    'uses' => 'Mqtt\AuthController@LHLHLoginSimple'
]);


Route::post('/LHLH/login', [
    'as' => 'post_LHLH_login',
    'uses' => 'Mqtt\AuthController@LHLHLogin'
]);


Route::get('/400/{error?}', [
    'as' => '400',
    'uses' => 'OpenWeb\IndexController@error_400'
]);

Route::get('/', [
    'as' => 'index',
    'uses' => 'OpenWeb\IndexController@index'
]);

Route::get('/device', [
    'as' => 'device',
    'uses' => 'OpenWeb\IndexController@device'
]);

Route::get('/developer', [
    'as' => 'developer',
    'uses' => 'OpenWeb\IndexController@developer'
]);

Route::any('/register/{step?}', [
    'as' => 'register',
    'uses' => 'OpenWeb\IndexController@register'
]);

Route::get('/search', [
    'as' => 'search',
    'uses' => 'OpenWeb\IndexController@search'
]);

Route::get('/forget', [
    'as' => 'forget',
    'uses' => 'OpenWeb\IndexController@forget'
]);

Route::get('/reset/password', [
    'as' => 'reset_password',
    'uses' => 'OpenWeb\IndexController@reset'
]);

Route::group([
    'middleware' => 'auth'
], function () {

    Route::get('/wiki', [
        'as' => 'wiki',
        'uses' => 'OpenWeb\IndexController@wiki'
    ]);

    Route::get('/password', [
        'as' => 'password',
        'uses' => 'OpenWeb\IndexController@password'
    ]);

    Route::get('/secret', [
        'as' => 'secrect',
        'uses' => 'OpenWeb\IndexController@secrect'
    ]);

    Route::get('/apply/{type?}', [
        'as' => 'apply',
        'uses' => 'OpenWeb\IndexController@apply'
    ]);


});

