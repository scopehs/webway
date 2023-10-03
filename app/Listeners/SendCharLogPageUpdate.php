<?php

namespace App\Listeners;

use App\Events\CharLogPageUpdate;

class SendCharLogPageUpdate
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
     * @param  \App\Events\CharLogPageUpdate  $event
     * @return void
     */
    public function handle(CharLogPageUpdate $event)
    {
        //
    }
}
