<?php

namespace App\Console\Commands\JumpRoute;

use App\Models\Connections\BlopsBridges;
use App\Models\Connections\Connections;
use App\Models\Connections\TitanBridges;
use Illuminate\Console\Command;

class populateTitanAndBlopsConnectionsDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:seed:connections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes the Static Data and Seeds the Connections Database.';

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
        // Type 10 - Titan Bridge
        // Type 11 - Blops Bridge

        $this->seedTitanBridgeConnections();
        $this->seedBlopsBridgeConnections();
    }

    /*
     * Add Titan Bridges to the Connection Table
     * <= 5 Lightyears
     */

    public function seedTitanBridgeConnections()
    {
        // Purge Existing Data
        Connections::where('type', 10)->delete();
        // Get the Titan Connections
        $bridge = TitanBridges::all();

        if ($bridge) {
            $this->output->progressStart(count($bridge));
            foreach ($bridge as $connection) {
                $this->output->progressAdvance();
                // Connection -> system_id is the key.
                $edges = json_decode($connection->range);

                /*
                39 => {#1275
                    +"source": 30000015
                    +"target": 30000108
                    +"lightyears": 3.26
                }
                */

                // Foreach Edge, within the Range, we should add the connection with the type of 10.
                foreach ($edges as $edge) {
                    // Create the connection
                    // This connection can be visualised as the following
                    // Source System = [ 'Gate', 'Gate', 'Gate', 'Gate']
                    // 1DQ-1 = [system_id, system_id, system_id, system_id]
                    // These systems are now connected via a bridge and the graph code will treat them as wormholes as such.

                    $connection = Connections::create([
                        'source_system_id' => $edge->source,
                        'target_system_id' => $edge->target,
                        'type' => 10,
                        'delete_flag' => 0,
                    ]);
                }
            }
            $this->output->progressFinish();
        }
    }

    /*
     * Add Blops Bridges to the Connection Table
     * <= 10 Lightyears
     */
    public function seedBlopsBridgeConnections()
    {
        // Purge Existing Data
        Connections::where('type', 11)->delete();
        // Get the Blops Connections
        $bridge = BlopsBridges::all();

        if ($bridge) {
            $this->output->progressStart(count($bridge));
            foreach ($bridge as $connection) {
                $this->output->progressAdvance();
                // Connection -> system_id is the key.
                $edges = json_decode($connection->range);

                /*
                39 => {#1275
                    +"source": 30000015
                    +"target": 30000108
                    +"lightyears": 3.26
                }
                */

                // Foreach Edge, within the Range, we should add the connection with the type of 10.
                foreach ($edges as $edge) {
                    // Create the connection
                    // This connection can be visualised as the following
                    // Source System = [ 'Gate', 'Gate', 'Gate', 'Gate']
                    // 1DQ-1 = [system_id, system_id, system_id, system_id]
                    // These systems are now connected via a bridge and the graph code will treat them as wormholes as such.

                    $connection = Connections::create([
                        'source_system_id' => $edge->source,
                        'target_system_id' => $edge->target,
                        'type' => 11,
                        'delete_flag' => 0,
                    ]);
                }
            }
            $this->output->progressFinish();
        }
    }
}
