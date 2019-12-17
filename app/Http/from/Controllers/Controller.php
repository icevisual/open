<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->setHeader();
    }

    protected $redisPrefiex = 'open';

    public function getRedisPrefix($key = 'default')
    {
        return $this->redisPrefiex . '-' . substr(sha1(static::class), 0, 6) . '-' . $key;
    }

    public static function setHeaders()
    {
        $header['Access-Control-Allow-Origin'] = '*';
        $header['Access-Control-Allow-Methods'] = 'GET, PUT, POST, DELETE, HEAD, OPTIONS';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept';
        
        if ($header) {
            foreach ($header as $head => $value) {
                header("{$head}: {$value}");
            }
        }
    }

    public static function setHeader()
    {
        static $_setted = false;
        if (! $_setted) {
            if (! \App::environment('testing')) {
                self::setHeaders();
                $_setted = true;
            }
            $_setted = true;
        }
    }

    /**
     *
     * @param number $code            
     * @param string $msg            
     * @param array $data            
     * @return \Illuminate\Http\JsonResponse
     */
    public function __json()
    {
        return call_user_func_array([
            \JsonReturn::class,
            'json'
        ], func_get_args());
    }

    /**
     *
     * @param number $code            
     * @param string $msg            
     * @param array $data            
     * @return \Illuminate\Http\JsonResponse
     */
    public function __jsonp()
    {
        return call_user_func_array([
            \JsonReturn::class,
            'jsonp'
        ], func_get_args());
    }
}
