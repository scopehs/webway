<?php

namespace App\Listeners;

use App\Events\StaticUpdate;

class SendStaticUpdate
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
     * @param  \App\Events\StaticUpdate  $event
     * @return void
     */
    public function handle(StaticUpdate $event)
    {
        //
    }
}
