<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\StatsHelper\StatsHelper;

class AllTheStatsPopulateHistoryJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $userID;

    protected $month;

    protected $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userID, $month, $year)
    {
        $this->userID = $userID;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        StatsHelper::statsStartOld($this->userID, $this->month, $this->year);
    }
}
