<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\StatsHelper\StatsHelper;

class updateAllTheStatsJobEveryone implements ShouldQueue
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
        StatsHelper::statsStartNewMassUpdate($this->userID);
    }
}
