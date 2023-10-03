<?php

namespace App\Listeners;

use App\Events\MappingUpdate;

class SendMappingUpdate
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
     * @param  MappingUpdate  $event
     * @return void
     */
    public function handle(MappingUpdate $event)
    {
        //
    }
}
