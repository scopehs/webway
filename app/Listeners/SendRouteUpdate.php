<?php

namespace App\Listeners;

use App\Events\RouteUpdate;

class SendRouteUpdate
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
     * @param  RouteUpdate  $event
     * @return void
     */
    public function handle(RouteUpdate $event)
    {
        //
    }
}
