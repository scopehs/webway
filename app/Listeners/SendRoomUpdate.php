<?php

namespace App\Listeners;

use App\Events\RoomUpdate;

class SendRoomUpdate
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
     * @param  \App\Events\RoomUpdate  $event
     * @return void
     */
    public function handle(RoomUpdate $event)
    {
        //
    }
}
