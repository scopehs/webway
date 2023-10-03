<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhaleUpdate implements ShouldBroadcastNow
{
    use Dispatchable;use InteractsWithSockets;use SerializesModels;

    public $flag;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($flag)
    {
        $this->flag = $flag;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('whalers');
    }
}
