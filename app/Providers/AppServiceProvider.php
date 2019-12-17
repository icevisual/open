<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        //
        \Validator::extend('mobile', function ($attribute, $value, $parameters) {
            return mobileCheck($value);
        });

        \Validator::extend('precision', function ($attribute, $value, $parameters) {
            $segments = explode('.', $value);
            return ! isset($segments[1]) || strlen(last($segments)) <= $parameters[0];
        });

        \Validator::extend('identity', function ($attribute, $value, $parameters) {
            return identityCardCheck($value);
        });

        \Validator::extend('specialChar', function ($attribute, $value, $parameters) {
            return nameContainNumberAndSpecialChar($value) === false;
        });

        \Validator::extend('checkCaptcha', function ($attribute, $value, $parameters) {
            return \App\Extensions\Verify\XmasCaptcha::checkCaptcha($value,array_get($parameters, '0','register'));
        });


        \Validator::extend('password', function ($attribute, $value, $parameters) {
            return preg_match('/[\w\`\=\-\!\@\#\$\%\^\&\*\(\)\_\+\~\;\:\"\'\<\>\/\,\.\?\[\]\{\}\|]{6,19}/', $value);
        });

        \DB::enableQueryLog();

        \DB::listen(function (\Illuminate\Database\Events\QueryExecuted $event) {
            \App::environment('local','testing') && \App\Services\Log\ServiceLog::sqlLog($event->sql, $event->bindings, $event->time);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
