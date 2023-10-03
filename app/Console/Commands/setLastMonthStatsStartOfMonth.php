<?php

namespace App\Console\Commands;

use App\Jobs\setLastMonthStatsStartOfMonthJob;
use App\Models\User;
use Illuminate\Console\Command;

class setLastMonthStatsStartOfMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes sure users have current and last month stats row';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ids = User::all();
        foreach ($ids as $id) {
            setLastMonthStatsStartOfMonthJob::dispatch($id->id);
        }
    }
}
