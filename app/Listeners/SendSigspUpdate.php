<?php

namespace App\Listeners;

use App\Events\SigspUpdate;

class SendSigspUpdate
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
     * @param  \App\Events\SigspUpdate  $event
     * @return void
     */
    public function handle(SigspUpdate $event)
    {
        //
    }
}
