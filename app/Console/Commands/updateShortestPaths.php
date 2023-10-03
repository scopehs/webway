<?php

namespace App\Console\Commands;

use App\Jobs\updateShortestPathsJob;
use App\Models\ShortestPath;
use Illuminate\Console\Command;

class updateShortestPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:shortestPaths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will update the shorest route for paths on shortest paths';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routes = ShortestPath::get();
        foreach ($routes as $route) {

            $id = $route->id;
            updateShortestPathsJob::dispatch($id)->onQueue('slow');
        }
    }
}
