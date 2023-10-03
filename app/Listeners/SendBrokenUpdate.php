<?php

namespace App\Listeners;

use App\Events\BrokenUpdate;

class SendBrokenUpdate
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
     * @param  \App\Events\BrokenUpdate  $event
     * @return void
     */
    public function handle(BrokenUpdate $event)
    {
        //
    }
}
