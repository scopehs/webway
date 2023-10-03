<?php

namespace App\Listeners;

use App\Events\CharUpdate;

class SendCharUpdate
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
     * @param  CharUpdate  $event
     * @return void
     */
    public function handle(CharUpdate $event)
    {
        //
    }
}
