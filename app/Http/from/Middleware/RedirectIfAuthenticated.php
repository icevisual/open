<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            $user = \Auth::getUser();
            
            if($user->email_activation == \App\Models\User\Account::EMAIL_ACTIVATION_NO){
                return redirect(route('register',['step' => 'step2']));
            }
            
            return redirect('/');
        }

        return $next($request);
    }
}
