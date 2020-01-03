<?php

namespace App\Policies;

use App\Models\User\Account;
use App\Models\Open\Device;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the device.
     *
     * @param  \App\Models\User\Account  $user
     * @param  \App\Models\Open\Device  $device
     * @return mixed
     */
    public function view(Account $user, Device $device)
    {
        //
    }

    /**
     * Determine whether the user can create devices.
     *
     * @param  \App\Models\User\Account  $user
     * @return mixed
     */
    public function create(Account $user)
    {
        //
    }

    /**
     * Determine whether the user can update the device.
     *
     * @param  \App\Models\User\Account  $user
     * @param  \App\Models\Open\Device  $device
     * @return mixed
     */
    public function update(Account $user, Device $device)
    {
        //
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @param  \App\Models\User\Account  $user
     * @param  \App\Models\Open\Device  $device
     * @return mixed
     */
    public function delete(Account $user, Device $device)
    {
        //
    }
}
