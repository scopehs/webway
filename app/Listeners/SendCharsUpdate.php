<?php

namespace App\Listeners;

use App\Events\ChartsUpdate;

class SendCharsUpdate
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
     * @param  \App\Events\ChartsUpdate  $event
     * @return void
     */
    public function handle(ChartsUpdate $event)
    {
        //
    }
}
