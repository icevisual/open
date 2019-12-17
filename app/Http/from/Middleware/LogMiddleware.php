<?php
namespace App\Http\Middleware;

use App\Services\Log\ServiceLog;
use Closure;

class LogMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Closure $next            
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        mt_mark('request-mt-start');
        $response = $next($request);
        ServiceLog::requestLog($request, $response->content(),$response->getStatusCode());
        return $response;
    }
}
