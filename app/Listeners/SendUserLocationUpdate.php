<?php

namespace App\Listeners;

use App\Events\UserLocationUpdate;

class SendUserLocationUpdate
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
     * @param  UserLocationUpdate  $event
     * @return void
     */
    public function handle(UserLocationUpdate $event)
    {
        //
    }
}
