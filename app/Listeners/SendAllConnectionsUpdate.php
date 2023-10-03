<?php

namespace App\Listeners;

use App\Events\AllConnectionsUpdate;

class SendAllConnectionsUpdate
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
     * @param  \App\Events\AllConnectionsUpdate  $event
     * @return void
     */
    public function handle(AllConnectionsUpdate $event)
    {
        //
    }
}
