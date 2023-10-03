<?php

namespace App\Listeners;

use App\Events\UserUpdate;

class SendUserUpdate
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
     * @param  UserUpdate  $event
     * @return void
     */
    public function handle(UserUpdate $event)
    {
        //
    }
}
