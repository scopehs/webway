<?php

namespace App\Console\Commands\SDE;

use App\Jobs\SDE\mapStargatesJob;
use App\Models\Connections\Connections;
use App\Models\SDE\mapSolarSystemJumps;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class createSystemGateConnectionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sde:map:stargates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an SQL table for connections between systems and stargates from EVE API';

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
        Connections::where('type', 1)->delete();

        $solar_systems = mapSolarSystemJumps::count();
        $bar = $this->output->createProgressBar($solar_systems);
        $bar->start();

        // Get Systems
        mapSolarSystemJumps::orderBy('fromSolarSystemID')->chunk(100, function ($systems) use ($bar) {
            foreach ($systems as $system_id) {
                // Push to Queue
                //$this->info('Dispatched ' . $system_id->fromSolarSystemID);

                $node = $system_id->fromSolarSystemID;
                $edge = $system_id->toSolarSystemID;

                $insert = new Connections();
                $insert->source_system_id = $node;
                $insert->target_system_id = $edge;
                $insert->type = 1;
                $insert->delete = 0;
                $insert->save();

                $bar->advance();

                //mapStargatesJob::dispatch($system_id->system_id);
            }
        });

        $bar->finish();
    }
}
