<?php

namespace App\Listeners;

use App\Events\LoginSuccEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginSuccListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginSuccEvent  $event
     * @return void
     */
    public function handle(LoginSuccEvent $event)
    {
        //
        $update = [
            'lastlogin_at' => date('Y-m-d H:i:s'),
            'prevlogin_at' => $event->getEventData('lastlogin_at'),
        ];
        \App\Models\User\Account::where('id', $event->getEventData('id'))->update($update);
    }
}
