<?php

namespace App\Listeners;

use App\Events\ShortestUpdate;

class SendShortestUpdate
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
     * @param  \App\Events\ShortestUpdate  $event
     * @return void
     */
    public function handle(ShortestUpdate $event)
    {
        //
    }
}
