<?php

namespace App\Providers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use sskaje\mqtt\Exception;
use App\Contracts\UserContract;

class AppServiceProvider extends ServiceProvider
{
    public function testResolv()
    {
        //  dg("UserProvider exists = ".class_exists(\App\Contracts\UserProvider::class));
        //  only this line ,Reflection Error dg("MultiUserProvider exists = ".class_exists(\App\Contracts\MultiUserProvider::class));
        $test_class = \App\Contracts\UserContract::class;
        // bind class
        $this->app->bind($test_class,\App\Contracts\UserProvider::class);

        // bind Closure
//        $this->app->bind($test_class, function ($app) {
//            return new \App\Contracts\UserProvider();
//        });
        // singleton
//        $this->app->singleton(\App\Contracts\UserContract::class, function ($app) {
//            return new \App\Contracts\UserProvider();
//        });
//        $api = new \App\Contracts\UserProvider();
//        // bind instance
//        $this->app->instance(\App\Contracts\UserContract::class, $api);
//        // provide ext variables
//        $this->app->when(\App\Contracts\UserProvider::class)
//            ->needs('$var')
//            ->give(function (){
//                return 33334;
//            });
        $this->app->when(\App\Contracts\UserProvider::class)
            ->needs(Filesystem::class)
            ->give(function (){
                return \Storage::disk("local");
            });

        $this->app->resolving($test_class, function ($api, $app) {
            // 当容器解析类型为「HelpSpot\API」的对象时调用...
            dg("resolving ".object_name($api));
        });

        $this->app->bind('SpeedReport', function () {
            //
            return [1,2,43];
        });

        $this->app->bind('MemoryReport', function () {
            //
            return [21,2,43];
        });

        $this->app->tag(['SpeedReport', 'MemoryReport'], 'reports');

//        $this->app->bind('ReportAggregator', function ($app) {
//            dump($app->tagged('reports'));
//            return "ffffff";
//        });
//
//        dump(resolve('ReportAggregator'));

        dg(resolve(\App\Contracts\UserContract::class)->GetUserById(12));
        //dg(resolve(\App\Contracts\UserContract::class)->GetUserById(12));
        // dg((new \App\Contracts\MultiUserProvider())->GetUserById(22));

        //dg("User2Provider exists = ".class_exists(\User2Provider::class));


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {





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
