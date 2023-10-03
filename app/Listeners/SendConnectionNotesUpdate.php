<?php

namespace App\Listeners;

use App\Events\ConnectionNotesUpdate;

class SendConnectionNotesUpdate
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
     * @param  \App\Events\ConnectionNotesUpdate  $event
     * @return void
     */
    public function handle(ConnectionNotesUpdate $event)
    {
        //
    }
}
