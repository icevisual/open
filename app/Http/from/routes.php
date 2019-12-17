<?php

if(\App::environment('local')){
    Route::match(['get','post'],'/test', 'Common\TestController@test');
    
    
    
    Route::match(['get','post'],'/test/jsonp', 'Common\TestController@testJsonp');
    
    
    
    
    Route::match(['get','post'],'/route', 'Common\RouteController@index');
}

Route::any('/iot/consumer', 'Common\TestController@server');

$router->group(['prefix' => 'api',], function () use ($router) {
    require(__DIR__ . "/Routes/api.php");
});

$router->group([
//     'middleware' => 'auth'
], function () use ($router) {
    require(__DIR__ . "/Routes/web.php");
});

Route::any('/sleep', function (){
    
    $s = \Input::get('s');
    
    usleep(1000000 * floatval($s));
    
    return \Response::json();
    
});

Route::any('/recordMaxAttampt', function (){
    $key = 'recordMaxAttampt';
    $n = \Input::get('n');
    if($n){
        $value = \LRedis::GET($key);
        if($value){
            $value .= ' '.$n;
        }else {
            $value = $n;
        }
        \LRedis::SETEX($key,86400,$value);
        return \Response::json(['status' => 's']);
    }else{
        return \Response::json(['status' => 'e']);
    }
});
    
    

