<?php
namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Extensions\Common\ConstTrait;

class PasswordModificationEvent extends Event
{
    use SerializesModels,ConstTrait;

    /**
     * 变更密码类型，创建账户
     * 
     * @var int
     */
    const CG_WAY_CREATE = 1;

    /**
     * 变更密码类型，重设密码
     * 
     * @var int
     */
    const CG_WAY_RESET = 2;

    /**
     * 变更密码类型，忘记密码
     * 
     * @var int
     */
    const CG_WAY_FORGET = 3;

    /**
     * 变更密码类型，主动修改
     * 
     * @var int
     */
    const CG_WAY_UPDATE = 4;

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
    
}
