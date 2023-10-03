<?php

namespace utils\RouteHelper;

use App\Http\Controllers\Helpers\JabberBot;
use App\Models\CharTracking;
use App\Models\Connections\Connections;
use App\Models\HotArea;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\SavedRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RouteHelper
{
    public static function path($request)
    {
        $start_system_id = $request['start_system_id'];               // int
        $finish_system_id = $request['finish_system_id'];             // int
        $mass = [3];                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $life = [3];                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $size = [];                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $avoid_systems = [];                   // Array(['system_ids']) - List of System IDs to Avoid
        $avoid_system_types = [];         // Array(['system_type']) - List of System Type IDs to Avoid - table:system_types
        $blops_systems = [];                     // Array(['system_ids']) - List of System IDs to Add Blops Bridge
        $titan_systems = [];                   // Array(['system_ids']) - List of System IDs to Add Titan Bridge
        $jump_bridge = true;                       // Boolean - True = Avoid
        $trusted = false;                             // Boolean - True = Use
        $link = Str::uuid();

        if (!$start_system_id || !$finish_system_id) {
            return null;
        }

        $settingsStart = [

            'avoid_system_types' => $avoid_system_types,
            'avoid_systems' => $avoid_systems,
            'blops_systems' => $blops_systems,
            'trusted' => $trusted,
            'finish_system_id' => $finish_system_id,
            'jump_bridge' => $jump_bridge,
            'life' => $life,
            'link' => $link,
            'mass' => $mass,
            'max_jumps' => 50,
            'size' => $size,
            'start_system_id' => $start_system_id,
            'titan_systems' => $titan_systems,

        ];

        $settings = json_encode($settingsStart);
        activity()->withoutLogs(function () use ($start_system_id, $finish_system_id, $settings, $link) {
            SavedRoute::create([
                'user_id' => Auth::id(),
                'start_system_id' => $start_system_id,
                'end_system_id' => $finish_system_id,
                'settings' => $settings,
                'saved' => 0,
                'link' => $link,
            ]);
        });

        $path = [];

        $static_data = RoutingStaticData::where('id', 1)->first();
        $graph = json_decode($static_data->data, true);
        $bridges = null;
        if ($jump_bridge) {
            $bridges = Connections::where('type', 3)
                ->get();
        }

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

        if (!empty($mass)) {
            $worm_query->whereHas('targetSig', function ($query) use ($mass) {
                $query->whereNotIn('wormhole_info_mass_id', $mass);
            });
        }

        if (!empty($size)) {
            $worm_query->whereHas('targetSig', function ($query) use ($size) {
                $query->whereNotIn('wormhole_info_ship_size_id', $size);
            });
        }

        if (!empty($life)) {
            $worm_query->whereHas('targetSig', function ($query) use ($life) {
                $query->whereNotIn('wormhole_info_time_till_death_id', $life);
            });
        }

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

        $return = [
            'jumps' => $total_jumps,
            'link' => $link,
        ];

        return $return;
    }



    public function microtime_float()
    {
        [$usec, $sec] = explode(' ', microtime());

        return ((float) $usec + (float) $sec) * 1000;
    }

    public static function jabberCheck($connectionID)
    {

        // return Helper::allTheStatsUsersByID(25107);
        // Jabber Annoucement
        $target = null;
        $source = null;
        $send = null;
        $jab = Connections::where('id', $connectionID)->with([
            'sourceSig',
            'targetSig',
            'type',
            'sourceSig.wormhole_type',
            'targetSig.wormhole_type',
            'targetSig.wormholeInfoLeadsTo',
            'sourceSig.wormholeInfoLeadsTo',
            'targetSig.wormholeInfoMass',
            'sourceSig.wormholeInfoMass',
            'targetSig.wormholeInfoShipSize',
            'sourceSig.wormholeInfoShipSize',
            'targetSig.wormholeInfoTimeTillDeath',
            'sourceSig.wormholeInfoTimeTillDeath',
            'sourceSig.solar_system',
            'sourceSig.solar_system.constellation',
            'sourceSig.solar_system.region',
            'targetSig.solar_system',
            'targetSig.solar_system.constellation',
            'targetSig.solar_system.region',
        ])->first();

        $targetSystemCheck = HotArea::where('system_id', $jab->targetSig->solar_system->system_id)->count();
        if ($targetSystemCheck) {
            $send = true;
            $target = true;
        }
        $targetRegionCheck = HotArea::where('region_id', $jab->targetSig->solar_system->region->region_id)->count();
        if ($targetRegionCheck) {
            $send = true;
            $target = true;
        }
        $targetConstellationCheck = HotArea::where('constellation_id', $jab->targetSig->solar_system->constellation->constellation_id)->count();
        if ($targetConstellationCheck) {
            $send = true;
            $target = true;
        }
        $sourceSystemCheck = HotArea::where('system_id', $jab->sourceSig->solar_system->system_id)->count();
        if ($sourceSystemCheck) {
            $send = true;
            $source = true;
        }
        $sourceRegionCheck = HotArea::where('region_id', $jab->sourceSig->solar_system->region->region_id)->count();
        if ($sourceRegionCheck) {
            $send = true;
            $source = true;
        }
        $sourceConstellationCheck = HotArea::where('constellation_id', $jab->sourceSig->solar_system->constellation->constellation_id)->count();
        if ($sourceConstellationCheck) {
            $send = true;
            $source = true;
        }

        $link = null;

        $size = $jab->sourceSig->wormholeInfoShipSize->table_text;
        $mass = $jab->sourceSig->wormholeInfoMass->text;
        $life = $jab->sourceSig->wormholeInfoTimeTillDeath->text;
        $sizeID = $jab->sourceSig->wormholeInfoShipSize->id;
        $massID = $jab->sourceSig->wormholeInfoMass->id;
        $lifeID = $jab->sourceSig->wormholeInfoTimeTillDeath->id;
        $source_system = $jab->sourceSig->solar_system->name;
        $target_system = $jab->targetSig->solar_system->name;
        $source_region = $jab->sourceSig->solar_system->region->name;
        $target_region = $jab->targetSig->solar_system->region->name;
        $source_constellation = $jab->sourceSig->solar_system->constellation->name;
        $target_constellation = $jab->targetSig->solar_system->constellation->name;
        // $target_type = $jab->targetSig->wormhole_type->wormhole_type;
        // $source_type = $jab->sourceSig->wormhole_type->wormhole_type;
        $source_system_id = $jab->source_system_id;
        $target_system_id = $jab->target_system_id;

        if ($massID == 3 || $lifeID == 3) {
            $send = false;
        }

        // Calculate route from 1DQ1-A
        if ($send == true) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $homeSystemID = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
            if ($source) {
                $return = RouteHelper::getRoute($homeSystemID, $source_system_id);
                if ($return['jumps'] <= 0) {
                    $send = false;
                }
                $link = 'https://webway.apps.gnf.lt/routing?share=' . $return['link'];
                $jab_message = 'New Connection : ' .
                    $size .
                    ' wormhole found, ' .
                    $mass .
                    ' mass with ' .
                    $life .
                    ' life. Leading to ' .
                    $source_region . '/' .
                    $source_constellation .
                    '/' .
                    $source_system .
                    ' ' .
                    $return['jumps'] .
                    ' Jumps from 1DQ (link: ' .
                    $link .
                    ' )';
            } else {
                $return = RouteHelper::getRoute($homeSystemID, $target_system_id);
                if ($return['jumps'] <= 0) {
                    $send = false;
                }
                $link = 'https://webway.apps.gnf.lt/routing?share=' . $return['link'];
                $jab_message = 'New Connection : ' .
                    $size .
                    ' wormhole found, ' .
                    $mass .
                    ' mass with ' .
                    $life .
                    ' life. Leading to ' .
                    $target_region . '/' .
                    $target_constellation .
                    '/' .
                    $target_system .
                    ' ' .
                    $return['jumps'] .
                    ' Jumps from 1DQ (link: ' .
                    $link .
                    ' )';
            }
        } else {
            Connections::where('id', $connectionID)->update(['reserved' => 0]);
        }
        if ($send == true) {
            JabberBot::post($jab_message);
            Connections::where('id', $connectionID)->update(['jabber_ping' => 1]);
        }
    }

    public static function getRoute($source, $target)
    {
        $mass = [3];
        $life = [3];
        $size = [];
        $jump_bridge = true;
        $avoid_thera = false;
        $trusted = false;

        $request_param = [
            'start_system_id'       => $source,
            'finish_system_id'      => $target,
            'mass'                  => $mass,
            'life'                  => $life,
            'size'                  => $size,
            'jump_bridge'           => $jump_bridge,
            'avoid_thera'           => $avoid_thera,
            'trusted'              => $trusted,

        ];

        $data = RouteHelper::path($request_param);
        $num = $data['jumps'] - 1;

        $return = [
            'jumps' => $num,
            'link' => $data['link'],
        ];

        return $return;
    }

    public static function updateTracking($systemID, $charID)
    {
        $lastRoute = CharTracking::where('character_id', $charID)->orderBy('id', 'desc')->first();
        if ($lastRoute) {
            $count = $lastRoute->count;
            $countAdd = $count + 1;
            CharTracking::create([
                'character_id' => $lastRoute->character_id,
                'current_system_id' => $systemID,
                'last_system_id' => $lastRoute->last_system_id,
                'ship_type_id' => $lastRoute->ship_type_id,
                'count' => $countAdd,
            ]);
        } else {
            CharTracking::create([
                'character_id' => $lastRoute->character_id,
                'current_system_id' => $systemID,
                'last_system_id' => $lastRoute->last_system_id,
                'ship_type_id' => $lastRoute->ship_type_id,
                'count' => 1,
            ]);
        }
    }
}
