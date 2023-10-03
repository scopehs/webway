<?php

namespace App\Listeners;

use App\Events\RoomsUpdate;

class SendRoomsUpdate
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
     * @param  \App\Events\RoomsUpdate  $event
     * @return void
     */
    public function handle(RoomsUpdate $event)
    {
        //
    }
}
