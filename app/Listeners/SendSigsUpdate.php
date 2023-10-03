<?php

namespace App\Listeners;

use App\Events\SigsUpdate;

class SendSigsUpdate
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
     * @param  \App\Events\SigsUpdate  $event
     * @return void
     */
    public function handle(SigsUpdate $event)
    {
        //
    }
}
