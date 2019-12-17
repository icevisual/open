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

