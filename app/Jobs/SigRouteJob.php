<?php

namespace App\Jobs;

use App\Models\Connections\Connections;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use utils\Helper\Helper;

class SigRouteJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $sigID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sigID)
    {
        $this->sigID = $sigID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->returnData($this->sigID);
    }

    public function returnData($sigID)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $start = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
        $start = (int) $start;
        $sig = Signature::where('id', $sigID)->first();
        $end = $sig->system_id;

        $link = $this->saveRoute($start, $end, 0);
        $link_p = $this->saveRoute($start, $end, 1);
        $jumps = $this->path($start, $end, 0);
        $jumps_p = $this->path($start, $end, 1);

        $oldLink = $sig->route_link ?? null;
        $oldLink_p = $sig->route_link_p ?? null;
        $oldJumps = $sig->jumps ?? null;
        $oldJumps_p = $sig->jumps_p ?? null;

        $sig->update([
            'route_link' => $link,
            'route_link_p' => $link_p,
            'jumps' => $jumps,
            'jumps_p' => $jumps_p,
        ], ['timestamps' => false]);

        if (
            $link != $oldLink ||
            $link_p != $oldLink_p ||
            $jumps != $oldJumps ||
            $jumps_p != $oldJumps_p
        ) {
            Helper::sigsBcastSolo($sig->id, 1);
        }

        soloBrokenCheck($sigID);
    }

    public function saveRoute($start, $end, $permission)
    {
        $settings = [
            'start_system_id' => $start,
            'finish_system_id' => $end,
            'mass' => [3],
            'avoid_system_types' => [],
            'life' => [3],
            'size' => [],
            'jump_bridge' => true,
            'trusted' => false,
            'avoid_systems' => [],
            'titan_systems' => [],
            'blops_systems' => [],
            'max_jumps' => 50,
            'permission' => $permission,
            'aviod_connections' => [],
            'type' => 'sigs',
        ];

        $settingAdd = json_encode($settings);

        $check = SavedRoute::where([
            ['user_id', 2],
            ['start_system_id', $start],
            ['end_system_id', $end],
            ['settings->type', 'sigs'],
            ['saved', 1],
        ])->first();

        if ($check) {
            $check->touch();
            $link = $check->link;
        } else {
            $link = Str::uuid();
            activity()->withoutLogs(function () use ($start, $end, $settingAdd, $link) {
                SavedRoute::create(
                    [
                        'user_id' => 2,
                        'start_system_id' => $start,
                        'end_system_id' => $end,
                        'settings' => $settingAdd,
                        'saved' => 1,
                        'link' => $link,
                    ]
                );
            });
        }

        return $link;
    }

    public function path($start, $end, $permission)
    {
        $start_system_id = $start;
        $finish_system_id = $end;
        $showReserved = $permission;

        if (!$start_system_id || !$finish_system_id) {
            return 0;
        }

        $path = [];
        $static_data = RoutingStaticData::where('id', 1)->first();
        $graph = json_decode($static_data->data, true);
        $bridges = null;
        $bridges = Connections::where('type', 3)->get();

        if ($bridges) {
            foreach ($bridges as $bridge) {
                array_push($graph[$bridge->source_system_id], $bridge->target_system_id);
                array_push($graph[$bridge->target_system_id], $bridge->source_system_id);
            }
        }

        $worm_query = Connections::query();
        $worm_query->whereIn('type', [2, 4, 5]);
        $worm_query->where('delete_flag', 0);
        $worm_query->whereNotNull('source_system_id');
        $worm_query->whereNotNull('target_system_id');
        $worm_query->whereNotNull('source_sig_id');
        $worm_query->whereNotNull('target_sig_id');

        if ($showReserved != 1) {
            $worm_query->where('reserved', 0);
        }

        $worm_query->whereHas('targetSig', function ($query) {
            $query->whereNotIn('wormhole_info_mass_id', [3]);
        });

        $worm_query->whereHas('targetSig', function ($query) {
            $query->whereNotIn('wormhole_info_mass_id', [3]);
        });

        $worm_query->whereHas('targetSig', function ($query) {
            $query->whereNotIn('wormhole_info_time_till_death_id', [3]);
        });

        $wormholes = $worm_query->get();

        if ($wormholes) {
            foreach ($wormholes as $wormhole) {
                isset($graph[$wormhole->source_system_id]) ?: $graph[$wormhole->source_system_id] = [];
                isset($graph[$wormhole->target_system_id]) ?: $graph[$wormhole->target_system_id] = [];
                array_push($graph[$wormhole->source_system_id], $wormhole->target_system_id);
                array_push($graph[$wormhole->target_system_id], $wormhole->source_system_id);
            }
        }


        foreach (findShortestPath($graph, $start_system_id, $finish_system_id, 100) as $n) {
            $path[] = $n;
        }

        $total_jumps = count($path);
        if ($total_jumps == 0) {
            return null;
        }

        if ($total_jumps == 1) {
            return 0;
        }
        $jumps = $total_jumps - 1;

        return $jumps;
    }
}
