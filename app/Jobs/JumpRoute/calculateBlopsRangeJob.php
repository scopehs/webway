<?php

namespace App\Jobs\JumpRoute;

use App\Models\Connections\BlopsBridges;
use App\Models\SDE\SolarSystem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class calculateBlopsRangeJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $system_id;

    protected $ignore_regions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($system_id, $ignore_regions)
    {
        $this->system_id = $system_id;
        $this->ignore_regions = $ignore_regions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->calculate_titan_nodes_ly($this->system_id, $this->ignore_regions);
    }

    public function calculate_titan_nodes_ly($system_id, $ignore_regions)
    {
        // Get all the systems and calculate the distance in lightyears
        // Cynos can only be lit in lowsec/nullsec, however a blops can bridge from any system.
        // Cynos cannot be lit in pochven, ffs.
        // We ignored pochven in the command createJumpRouteBlopsStaticDataCommand, as we can bridge from there, however..
        // We need to add it to the ignore regions now, when we are searching for nodes, because we can't light a cyno in there.

        array_push($ignore_regions, 10000070); // Pushing in Pochven. Duh!

        $nodes = SolarSystem::whereNotIn('region_id', $ignore_regions)
            ->where('security', '<', 0.45)
            ->get();

        // Cycle each target node and check jump range.
        foreach ($nodes as $node) {
            // Whats the distance between $system and $node.
            $result = $this->calculate_jump_distance($system_id, $node->system_id);

            if ($result) {
                if ($result['lightyears'] <= 8) {
                    // Found System and Target within 10 Light Years, Add it to the Static Data.
                    $response[] = $result;
                }
            }
        }

        // Array is Built, for 1 System. Save this.
        BlopsBridges::updateOrCreate([
            'system_id'                   => $system_id,
        ], [
            'range'                       => json_encode($response),
        ]);

        // Reset the Array
        $response = [];
    }

    public function calculate_jump_distance($source, $target)
    {
        $first = SolarSystem::where('system_id', $source)->first();
        $second = SolarSystem::where('system_id', $target)->first();

        // Calculate Jump Distance
        // https://web.archive.org/web/20210925103306/https://eve-search.com/thread/1236569-0
        // https://web.archive.org/web/20210717161148/https://evemaps.dotlan.net/blog/2009/09/09/minor-jumpdistance-calculation-bug-fixed/
        // 9460000000000000.0 is used for lightyears in meters, because CCP are bad.
        // Distance calculation is based on CCP's lightyear being 9460000000000000 meters, instead of the actual value of 9460730472580800.

        $distance = sqrt(pow($first->x - $second->x, 2) + pow($first->y - $second->y, 2) + pow($first->z - $second->z, 2)) / 9460000000000000.0;

        $response = [
            'source'        => $source,
            'target'        => $target,
            'lightyears'    => round($distance, 2),
        ];

        if ($distance) {
            return $response;
        } else {
            return null;
        }
    }
}
