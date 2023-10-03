<?php

use App\Models\Connections\Connections;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\SavedRoute;
use App\Models\ShortestPath;
use Illuminate\Support\Str;

if (!function_exists('allShortest')) {
    function allShortest()
    {
        $shortest = ShortestPath::with([
            "startSystem:system_id,name",
            'endSystem:system_id,name',
            'user:id,name'
        ])->get();

        return $shortest;
    }
}

if (!function_exists('soloShortest')) {
    function soloShortest($id)
    {
        $shortest = ShortestPath::whereId($id)
            ->with([
                "startSystem:system_id,name",
                'endSystem:system_id,name',
                'user:id,name'
            ])->first();

        return $shortest;
    }
}



if (!function_exists('deleteShortest')) {
    function deleteShortest($id)
    {
        $shortest = ShortestPath::whereId($id)->first();
        $shortest->delete();
    }
}

if (!function_exists('getShortestRouteStart')) {
    function getShortestRouteStart($startID, $endID)
    {

        $link = shortestLink($startID, $endID);
        $jumps = shortestJumps($startID, $endID, 0);

        return $data = collect([
            'link' => $link,
            'jumps' => $jumps,
        ]);
    }
}

if (!function_exists('shortestLink')) {
    function shortestLink($start, $end)
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
            'permission' => 1,
            'aviod_connections' => [],
            'type' => "shortest"
        ];

        $settingAdd = json_encode($settings);

        $check = SavedRoute::where([
            ['user_id', 2],
            ['start_system_id', $start],
            ['end_system_id', $end],
            ['settings->type', 'shortest'],
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
}

if (!function_exists('shortestJumps')) {
    function shortestJumps($start, $end)
    {
        $start_system_id = $start;
        $finish_system_id = $end;
        $showReserved = 1;


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
