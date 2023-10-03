<?php

namespace App\Listeners;

use App\Events\TrackingAdd;

class SendTrackingAdd
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
     * @param  TrackingAdd  $event
     * @return void
     */
    public function handle(TrackingAdd $event)
    {
        //
    }
}
