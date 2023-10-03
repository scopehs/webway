<?php

namespace App\Console\Commands;

use App\Models\SavedRoute;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanUpEveStuffRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:evestuffroutes';

    /**
     * The console commandffff description.
     *
     * @var string
     */
    protected $description = 'Removes all routes made by evestuff that have not been updated in the last hour';

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
        SavedRoute::where('user_id', 1)->where('updated_at', '<', Carbon::now()->subHour())->delete();
    }
}
