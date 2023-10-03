<?php

namespace App\Listeners;

use App\Events\AllTheStatsUpdate;

class SendAllTheStatsUpdate
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
     * @param  AllTheStatsUpdate  $event
     * @return void
     */
    public function handle(AllTheStatsUpdate $event)
    {
        //
    }
}
