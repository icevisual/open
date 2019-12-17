<?php

namespace App\Listeners;

use App\Events\PasswordModificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Company\CompanyAccountMo;

class PasswordModificationListener
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
     * @param  PasswordModificationEvent  $event
     * @return void
     */
    public function handle(PasswordModificationEvent $event)
    {
        $way = $event->getEventData('way');
        $uid = $event->getEventData('uid');
        $wayArray = [
            PasswordModificationEvent::CG_WAY_FORGET,
            PasswordModificationEvent::CG_WAY_UPDATE,
        ];
        \Com::debug($uid.' changed pwd by '.PasswordModificationEvent::detectConstName($way));
    }
}
