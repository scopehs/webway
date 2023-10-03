<?php

namespace App\Listeners;

use App\Events\AllTheStatsUpdateUser;

class SendAllTheStatsUpdateUser
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
     * @param  \App\Events\AllTheStatsUpdateUser  $event
     * @return void
     */
    public function handle(AllTheStatsUpdateUser $event)
    {
        //
    }
}
