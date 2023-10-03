<?php

namespace App\Console\Commands;

use App\Jobs\updateAllTheStatsJobEveryone;
use App\Models\User;
use Illuminate\Console\Command;

class updateAllCurrentStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:currentstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all the stats for current month';

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
        $users = User::all()->pluck('id');
        foreach ($users as $user) {
            updateAllTheStatsJobEveryone::dispatch($user);
        }
    }
}
