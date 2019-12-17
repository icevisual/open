<?php

namespace App\Extensions\Common;

use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Lang;

trait Throttles
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  String  $throttleKey
     * @return bool
     */
    protected function hasTooManyAttempts($throttleKey)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $throttleKey,
            $this->maxAttempts(), $this->lockoutTime() / 60
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  String  $throttleKey
     * @return int
     */
    protected function incrementAttempts($throttleKey)
    {
        app(RateLimiter::class)->hit(
            $throttleKey
        );
    }

    /**
     * Determine how many retries are left for the user.
     *
     * @param  String  $throttleKey
     * @return int
     */
    protected function retriesLeft($throttleKey)
    {
        return app(RateLimiter::class)->retriesLeft(
            $throttleKey,
            $this->maxAttempts()
        );
    }

    /**
     * Get the login lockout error message.
     *
     * @param  int  $seconds
     * @return string
     */
    protected function getLockoutErrorMessage($seconds)
    {
        return Lang::has('auth.throttle')
            ? Lang::get('auth.throttle', ['seconds' => $seconds])
            : 'Too many login attempts. Please try again in '.$seconds.' seconds.';
    }

    /**
     * Get the lockout seconds.
     *
     * @param  String  $throttleKey
     * @return int
     */
    protected function secondsRemainingOnLockout($throttleKey)
    {
        return app(RateLimiter::class)->availableIn(
            $throttleKey
        );
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param  String $throttleKey
     * @return void
     */
    protected function clearAttempts($throttleKey)
    {
        app(RateLimiter::class)->clear(
            $throttleKey
        );
    }


    /**
     * Set the maximum number of attempts for delaying further attempts.
     *
     * @return int
     */
    protected function setMaxAttempts($max)
    {
        $this->maxAttempts = $max;
    }
    
    /**
     * Set The number of seconds to delay further attempts.
     *
     * @return int
     */
    protected function setLockoutTime($time)
    {
        $this->lockoutTime = $time;
    }
    
    /**
     * Get the maximum number of login attempts for delaying further attempts.
     *
     * @return int
     */
    protected function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    /**
     * The number of seconds to delay further login attempts.
     *
     * @return int
     */
    protected function lockoutTime()
    {
        return property_exists($this, 'lockoutTime') ? $this->lockoutTime : 60;
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }
}
