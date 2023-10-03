<?php

namespace App\Console\Commands\Graph;

use App\Models\Connections\Connections;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\SDE\SolarSystem;
use Illuminate\Console\Command;

class GraphPathCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graph:path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Graph the path, create the solar systems graph of static systems.';

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
        $this->path();
    }

    public function path()
    {
        $this->info('Building the Graph');
        $time_start = $this->microtime_float();
        $systems = SolarSystem::all();

        // Build the static data.
        foreach ($systems as $system) {
            $edge = Connections::where('source_system_id', $system->system_id)->where('type', 1)->where('delete_flag', 0)->get();
            foreach ($edge as $node) {
                $nodes[] = $node->target_system_id;
            }

            $edges[$node->source_system_id] = $nodes;
            $nodes = [];
        }

        $path = [];

        $time_end = $this->microtime_float();
        $time = round($time_end - $time_start, 3).' ms';
        /*
        if ($file = fopen('database/json/graph/static_systems.json', 'w')) {
            fwrite($file, json_encode($edges));
            fclose($file);
        }
        */

        RoutingStaticData::updateOrCreate([
            'id'                         => 1,
        ], [
            'data'                       => json_encode($edges),
        ]);

        $this->info('Build took '.$time);
    }

    public function microtime_float()
    {
        [$usec, $sec] = explode(' ', microtime());

        return ((float) $usec + (float) $sec) * 1000;
    }
}
