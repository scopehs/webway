<?php

namespace App\Jobs\SDE;

use App\Models\Connections\Connections;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Seat\Eseye\Eseye;
use Seat\Eseye\Exceptions\RequestFailedException;

class mapStargatesJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $system_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($system_id)
    {
        $this->system_id = $system_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Each System, Get the system data.
        $system = $this->getSystem($this->system_id);
        //$this->info($system->name);

        // Does the system have stargates..
        if (isset($system->stargates)) {
            // Has Stargates, Where do they lead..
            // Is an array, so cycle through.
            //$this->info('Stargates:');
            foreach ($system->stargates as $stargate_id) {

                // Get the stargate data.
                $stargate = $this->getStargate($stargate_id);

                $node = $this->system_id;
                $edge = $stargate->destination->system_id;

                $insert = new Connections();
                $insert->source_system_id = $node;
                $insert->target_system_id = $edge;
                $insert->type = 1;
                $insert->delete = 0;
                $insert->save();
            }
        }
    }

    /**
     * https://esi.evetech.net/ui/#/operations/Universe/get_universe_stargates_stargate_id
     *
     * @return array
     */
    public function getStargate($stargate_id)
    {
        $esi = new Eseye();

        try {
            $response = $esi->invoke('get', '/universe/stargates/{stargate_id}', [
                'stargate_id' => $stargate_id,
            ]);
        } catch (RequestFailedException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }

        return $response;
    }

    /**
     * https://esi.evetech.net/ui/#/operations/Universe/get_universe_systems_system_id
     *
     * @return array
     */
    public function getSystem($system_id)
    {
        $esi = new Eseye();

        try {
            $response = $esi->invoke('get', '/universe/systems/{system_id}', [
                'system_id' => $system_id,
            ]);
        } catch (RequestFailedException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }

        return $response;
    }
}
