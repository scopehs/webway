<?php

namespace utils\Gasstationhelper;

use App\Http\Controllers\Helpers\JabberBot;
use App\Models\Connections\Connections;
use App\Models\Nebula;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\Scanning\Signature;
use App\Models\System;

class Gasstationhelper
{
    public static function ping($id)
    {
        $hotGas = Nebula::where('jabber', 1)->get();
        $hotNames = $hotGas->pluck('name');
        $sig = Signature::where('id', $id)->where('jabber_ping', 0)->whereIn('name', $hotNames)->first();
        if ($sig) {
            $channel = 'gas_station';
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $homeSystemID = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);

            $systemName = $sig->solar_system->name;
            $regionName = $sig->solar_system->region->name;
            // $return = ($homeSystemID, $sig->system_id);
            $return = Gasstationhelper::path($homeSystemID, $sig->system_id, 0);
            if ($return != null) {
                $siteURL = env('APP_URL', ($variables && array_key_exists('APP_URL', $variables)) ? $variables['APP_URL'] : null);
                $url = 'https://' . $siteURL . '/sigs?sigID=' . $sig->id;
                $text = $sig->name .
                    ' gas site found in ' .
                    $regionName .
                    ' - ' .
                    $systemName .
                    '.  ' .
                    $return .
                    ' jumps away from 1DQ - ' . $url;

                JabberBot::post($text, $channel);
                $sig->jabber_ping = 1;
                $sig->save();
            }
        }

        $newSig = Signature::where('id', $id)->first();
        if ($newSig->signature_group_id != 4) {
            $newSig->jabber_ping = 1;
            $newSig->save();
        }
    }

    public static function path($start, $end, $permission)
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
