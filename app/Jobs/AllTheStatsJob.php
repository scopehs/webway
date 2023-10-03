<?php

namespace App\Jobs;

use App\Events\AllTheStatsUpdate;
use App\Events\AllTheStatsUpdateUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\StatsHelper\StatsHelper;

class AllTheStatsJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $userID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = StatsHelper::statsStartNew($this->userID);

        $flag = collect([
            'flag' => 1,
            'message' => $message,
        ]);
        broadcast(new AllTheStatsUpdate($flag));

        $flag = collect([
            'flag' => 1,
            'user_id' => $this->userID,
            'message' => $message,
        ]);
        broadcast(new AllTheStatsUpdateUser($flag));
    }
}
