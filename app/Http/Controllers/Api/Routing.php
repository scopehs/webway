<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Connections\Connections;
use App\Models\RoutingStatic\RoutingStaticData;
use App\Models\SavedRoute;
use App\Models\SDE\SolarSystem;
use App\Models\SystemSystemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Routing extends Controller
{
    use HasRoles;
    use HasPermissions;

    public function path(Request $request)
    {
        $start_system_id = $request->start_system_id;               // int
        $finish_system_id = $request->finish_system_id;             // int
        $mass = $request->mass;                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $life = $request->life;                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $size = $request->size;                                     // Array([1,2,3]) # 1 Stage 1, 2, Stage 2, 3 Stage 3.
        $avoid_systems = $request->avoid_systems;                   // Array(['system_ids']) - List of System IDs to Avoid
        $avoid_system_types = $request->avoid_system_types;         // Array(['system_type']) - List of System Type IDs to Avoid - table:system_types
        $blops_systems = $request->blops_systems;                     // Array(['system_ids']) - List of System IDs to Add Blops Bridge
        $titan_systems = $request->titan_systems;                   // Array(['system_ids']) - List of System IDs to Add Titan Bridge
        $jump_bridge = $request->jump_bridge;                       // Boolean - True = use
        $trusted = $request->trusted;                             // Boolean - True = Use
        $permission = $request->permission;                         // Boolean - Checks if the link has use reserved connections set.
        $aviod_connections = $request->aviod_connections;           // array(['connection_id']) - List of Connections to Avoid
        $use_genesis = $request->use_genesis;                //true = can use genesis hole

        if (!$start_system_id || !$finish_system_id) {
            return null;
        }

        /*
        $this->info('Calculating the shortest route.');
        $g = array();
        $g[1] = array( 2, 3 );
        $g[2] = array( 4, 6 );
        $g[3] = array( 12, 13 );
        $g[4] = array( 3, 7, 20 );
        $g[5] = array( 1, 2, 3, 4 );
        $g[6] = array( 1, 2, 3, 4, 5, 9 );
        foreach( $this->graph_find_path( $g, 1, 9 ) as $n ) {
        $this->info($n);
        }
        */

        // So to as a system example, we would import all systems with their directly related systems
        // [1DQ1-A] = [T5ZI-S, N-8YET, MO-GZ5]
        // [J0000123] = [J000456, J000789, MO-GZ5]
        // The function will map the route, from J000456 array, through J0000123 key, through 1DQ1-A to MO-GZ5.
        /*
        $g = array();
        $g[1] = array( 2, 3 );
        $g[2] = array( 4, 6 );
        $g[3] = array( 12, 13 );
        $g[4] = array( 3, 7 );
        $g[5] = array( 1, 2, 3, 4 );
        $g[6] = array( 1, 2, 3, 4, 5 );
        */
        /*
        //$this->info('Calculating the shortest route. From Y-OMTZ, To PDE-U3');
        $g = array();

        # 1DQ Constellation
        $g['1DQ1-A'] = array('T5ZI-S', 'N-8YET', 'MO-GZ5');
        $g['Y-OMTZ'] = array('5BTK-M');
        $g['5BTK-M'] = array('8WA-Z6');
        $g['8WA-Z6'] = array('MO-GZ5', '1DQ1-A');
        //$g['8WA-Z6'] = array('MO-GZ5', '1DQ1-A', 'J000420');
        $g['MO-GZ5'] = array('8WA-Z6', '1DQ1-A');
        $g['T5ZI-S'] = array('1DQ1-A', 'D-W7F0');
        $g['N-8YET'] = array('3-DMQT', '1DQ1-A');
        # Treat this system as end of line and cut the route.
        # Even though its not.
        $g['3-DMQT'] = array();

        # PDE Constellation

        $g['D-W7F0'] = array('4K-TRB', 'JP4-AA');
        $g['JP4-AA'] = array('D-W7F0', '23G-XC', '4X0-8B', 'FM-JK5');
        //$g['JP4-AA'] = array('D-W7F0', '23G-XC', '4X0-8B', 'FM-JK5', 'J000420');
        $g['23G-XC'] = array('JP4-AA');
        $g['4X0-8B'] = array('FM-JK5', 'KEE-N6', 'JP4-AA');
        $g['FM-JK5'] = array('PDE-U3', '4X0-8B', 'JP4-AA');
        $g['PDE-U3'] = array('FM-JK5');
        # Treat this system as end of line and cut the route.
        # Even though its not.
        $g['4K-TRB'] = array();

        # Introduce a Wormhole
        # From Say 8WA to JP4

        //$g['J000420'] = array('8WA-Z6', 'J000444');
        //$g['J000444'] = array('J000420', 'JP4-AA');
        */

        // Build a Multidimensional array from connections table.
        // This has been replaced by graph:path, were static data is built into a json file to be pulled into memory.
        // Static Data takes approx 63 seconds to build.
        // Dynamic Data will be added by adding connections to the static data by array_push.
        /*
        $systems = SolarSystem::where('region_id', 10000060)->get();


        foreach($systems as $system) {
            $edge = Connections::where('source_system_id', $system->system_id)->where('type', 1)->where('delete', 0)->get();
            foreach($edge as $node) {
                $nodes [] = $node->target_system_id;
            }

            $edges [$node->source_system_id] = $nodes;
            $nodes = array();
        }

        /* Temp Shit */
        /*

        $edges ['30004028'] = array();
        $edges ['30004665'] = array();
        $edges ['30004013'] = array();
        $edges ['30003967'] = array();
        $edges ['30004927'] = array();
        $edges ['30004027'] = array();
        $edges ['30004960'] = array();
        $edges ['30004012'] = array();
        $edges ['30004299'] = array();

        ## Define Connections / Wormholes, To Override Static Data.

        # Add wormhole to 30004797

        array_push($edges['30004797'], 31000249);

        # Make the wormhole;

        $edges ['31000249'] = array(30004797, 30004769);

        # Spawn a wormhole, in another system

        array_push($edges['30004769'], 31000249);

        ## End of Testing..
        */

        $time_start = $this->microtime_float();
        $path = [];

        // $static_data imported from static json file.
        $static_data = RoutingStaticData::where('id', 1)->first();
        $graph = json_decode($static_data->data, true);

        // Dynamic Data Import
        // I.e Wormhole Conenctions / Thera / Jump Bridges / Blops and anything else we can think of.
        // Will be a filtered query but later 19/9/21

        // Import Jump Bridges
        $bridges = null;

        if ($jump_bridge) {
            if (empty($aviod_connections)) {
                $bridges = Connections::where('type', 3)
                    ->get();
            } else {
                $bridges = Connections::where('type', 3)->whereNotIn('id', $aviod_connections)
                    ->get();
            }
        }
        /*
        "source_system_id" => 30004788
        "target_system_id" => 30004770
        */

        if ($bridges) {
            foreach ($bridges as $bridge) {
                /*
                30000001 => array:3 [
                    0 => 30000003
                    1 => 30000005
                    2 => 30000007
                ]
                */
                array_push($graph[$bridge->source_system_id], $bridge->target_system_id);
                array_push($graph[$bridge->target_system_id], $bridge->source_system_id);
            }
        }
        // End of Bridges

        // Check if Thera is on the system avoid list.

        // Import Wormhole Connections
        $worm_query = Connections::query();

        // Define Filter Types Based on Systems.
        // Thera is not excluded, so include it in connection search.
        //$types = array();
        //if(!in_array(12, $avoid_system_types, true)) { array_push($types, 4); }

        // Get wormhole type, not deleted and filter out incomplete shit.
        $worm_query->whereIn('type', [2, 4, 5]); // 2, Wormholes, 4 Thera, 5 Pochven
        $worm_query->where('delete_flag', 0);
        $worm_query->whereNotNull('source_system_id');
        $worm_query->whereNotNull('target_system_id');
        $worm_query->whereNotNull('source_sig_id');
        $worm_query->whereNotNull('target_sig_id');
        if (!FacadesAuth::user()->can('use_genesis')) {
            if (!$use_genesis) {
                $worm_query->whereNotIn('source_system_id', [31001781, 31001871]);
                $worm_query->whereNotIn('target_system_id', [31001781, 31001871]);
            }
        }

        if (!empty($aviod_connections)) {
            $worm_query->whereNotIn('id', $aviod_connections);
        }

        if (!empty($trusted)) {
            $worm_query->whereIn('trusted', [1]);
        }

        // Filter are here...
        // Mass
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

        // Exclude Drifter/Thera
        if ($avoid_system_types) {
            $worm_query->whereHas('systemTypeTarget', function ($query) use ($avoid_system_types) {
                $query->whereNotIn('system_type_id', $avoid_system_types);
            });

            $worm_query->whereHas('systemTypeSource', function ($query) use ($avoid_system_types) {
                $query->whereNotIn('system_type_id', $avoid_system_types);
            });
        }

        if (FacadesAuth::user()->can('use_reserved_connection')) {
            $worm_query->whereIn('reserved', [0, 1, 2]);
        } else {
            if ($request->permission == 1) {
                $worm_query->whereIn('reserved', [0, 1, 2]);
            } else {
                // $worm_query->where('reserved', 0)->orWhere('completed_user_id', FacadesAuth::id());
                $worm_query->where('reserved', 0)->orwhere(function ($q) {
                    $q->where('reserved', 1)->where('completed_user_id', FacadesAuth::id());
                });
                // $worm_query->orWhere('completed_user_id', FacadesAuth::id());
            }
        }

        // Get wormhole
        $wormholes = $worm_query->get();

        //return ($wormholes);
        if ($wormholes) {
            // Map Systems - By Building Up Graph Array for J Codes.
            foreach ($wormholes as $wormhole) {
                // Init Arrays
                // Check if it aleady exists and if it doesn't init it.
                isset($graph[$wormhole->source_system_id]) ?: $graph[$wormhole->source_system_id] = [];
                isset($graph[$wormhole->target_system_id]) ?: $graph[$wormhole->target_system_id] = [];

                // All J Codes added to Graph Map.
                array_push($graph[$wormhole->source_system_id], $wormhole->target_system_id);
                array_push($graph[$wormhole->target_system_id], $wormhole->source_system_id);
            }
        }
        //End Wormholes

        // If Pochven is to be avoided, the above won't work, we need to purge it manually from the graph.
        if (in_array(25, $avoid_system_types)) {
            // Get all Pochven Systems
            // If you want to avoid pochven, rebuild the pochven graph.
            $pochven = SystemSystemType::where('system_type_id', 25)->get();
            // Build the static data for pochven only.
            foreach ($pochven as $system) {
                $edge = Connections::where('source_system_id', $system->system_id)->where('type', 1)->where('delete_flag', 0)->get();
                foreach ($edge as $node) {
                    $nodes[] = $node->target_system_id;
                }

                // Overwriting Static Data.
                $graph[$node->source_system_id] = $nodes;
                $nodes = [];
            }
        }

        // Titan Systems
        if ($titan_systems) {
            // Oh goodie, we have a titan system to bridge from!
            // Lets get all those connections in

            foreach ($titan_systems as $titan) {
                // return int
                // Lets take 1 system at a time incae we have more than one, sneaky buggers.

                $titan_connections = Connections::where('source_system_id', $titan)
                    ->where('type', 10) // Type 10 is a Titan, Type 11 is a Blops, 5ly or 10ly, you decide!
                    ->where('delete_flag', 0)
                    ->get();

                //return($titan_connections);

                // returns an array that we need to deal with.
                // source_id -> target_id
                // identical every time, target changes.
                // add to graph, through key and add the edges.
                // sanity check

                foreach ($titan_connections as $titan_edge) {
                    array_push($graph[$titan_edge->source_system_id], $titan_edge->target_system_id);
                    //array_push($graph[$titan_edge->target_system_id], $titan_edge->source_system_id);

                    /* Pondering, maybe we should check that the system isn't already in that existing multi dimensional array */
                    /* come back to this point, if shit breaks. */
                    // Push in the source system target edge
                    //if(!in_array($titan_edge->target_system_id, $graph[$titan_edge->source_system_id]))
                    //{
                    //array_push($graph[$titan_edge->source_system_id], $titan_edge->target_system_id);
                    //}

                    // Reverse, push in from the other target system and add in the source.
                    // a bridge requires an entry and exit silly and we don't want mr dik getting stuck.

                    //if(!in_array($titan_edge->source_system_id, $graph[$titan_edge->target_system_id]))
                    //{
                    //array_push($graph[$titan_edge->target_system_id], $titan_edge->source_system_id);
                    //}
                }
            }
        }

        // Blops Bridge Systems
        if ($blops_systems) {
            // Oh goodie, we have a blops system to bridge from!
            // Lets get all those connections in

            foreach ($blops_systems as $blops) {
                // return int
                // Lets take 1 system at a time incae we have more than one, sneaky buggers.

                $blops_connections = Connections::where('source_system_id', $blops)
                    ->where('type', 11) // Type 10 is a blops, Type 11 is a Blops, 5ly or 10ly, you decide!
                    ->where('delete_flag', 0)
                    ->get();

                //return($blops_connections);

                // returns an array that we need to deal with.
                // source_id -> target_id
                // identical every time, target changes.
                // add to graph, through key and add the edges.
                // sanity check

                foreach ($blops_connections as $blops_edge) {
                    array_push($graph[$blops_edge->source_system_id], $blops_edge->target_system_id);
                    //array_push($graph[$blops_edge->target_system_id], $blops_edge->source_system_id);

                    /* Pondering, maybe we should check that the system isn't already in that existing multi dimensional array */
                    /* come back to this point, if shit breaks. */
                    // Push in the source system target edge
                    //if(!in_array($blops_edge->target_system_id, $graph[$blops_edge->source_system_id]))
                    //{
                    //array_push($graph[$blops_edge->source_system_id], $blops_edge->target_system_id);
                    //}

                    // Reverse, push in from the other target system and add in the source.
                    // a bridge requires an entry and exit silly and we don't want mr dik getting stuck.

                    //if(!in_array($blops_edge->source_system_id, $graph[$blops_edge->target_system_id]))
                    //{
                    //array_push($graph[$blops_edge->target_system_id], $blops_edge->source_system_id);
                    //}
                }
            }
        }

        // Avoid System
        // If a user wishes to avoid a system, we should rebuild the connections without that system.
        if ($avoid_systems) {
            // Get all the avoid Systems
            // Rebuild the graph.
            // Build the static data for avoid systems only.
            foreach ($avoid_systems as $system) {

                // Remove the system from the graph
                $graph[$system] = [];

                // Get all the connections related to the solar system.
                $edge = Connections::where('source_system_id', $system)
                    ->where('type', 1)
                    ->where('delete_flag', 0)
                    ->get();

                // $edge is an array of all the known connections related to the system.
                // rebuild those systems from the connection data.

                foreach ($edge as $node) {
                    // Get the known connections to this node.
                    $gates = Connections::where('source_system_id', $node->target_system_id)
                        ->where('type', 1)
                        ->where('delete_flag', 0)
                        ->where('target_system_id', '!=', $system)
                        ->get();

                    // Rebuild the graph for known connections to that system, without the system.
                    // Cycle each gate and add it to the system.
                    foreach ($gates as $build) {
                        $nodes[] = $build->target_system_id;
                    }
                    // Overwriting Static Data.
                    $graph[$node->target_system_id] = $nodes;
                    $nodes = [];
                }
            }
        }

        //return $graph;



        foreach (findShortestPath($graph, $start_system_id, $finish_system_id, 100) as $n) {
            $path[] = $n;
        }


        $time_end = $this->microtime_float();
        $time = round($time_end - $time_start, 3) . ' ms';

        $detailed_path = [];

        $total_jumps = count($path);
        $jumped = 0;

        $connectionTypes = [1, 2, 4, 5];

        if ($jump_bridge) {
            array_push($connectionTypes, 3);
        }

        if ($blops_systems) {
            array_push($connectionTypes, 11);
        }

        if ($titan_systems) {
            array_push($connectionTypes, 10);
        }

        // Got entire path of solar systems, append the connection details.
        $text = null;
        while ($jumped < $total_jumps) {
            $system = SolarSystem::where('system_id', $path[$jumped])
                ->with(['constellation', 'region', 'systemType'])
                ->first();

            // Cycle through entire path and find the source and tagrte in the connections table based on system id.
            // When we come to the final system, we are in the target system, so we do not care.
            // So only run the query on the connections, if jumped doesn't equal the total jumps.

            if ($jumped < ($total_jumps - 1)) {
                /*
                # Jump ahead in the array to find the target system.
                # If we try to add plus 1 to the array in the target system, it will break.
                $connection = Connections::where('source_system_id', $path[$jumped])
                ->orWhere('target_system_id', $path[$jumped])
                ->orWhere('target_system_id', $path[$jumped - 1])
                ->with(['sourceSig', 'type'])
                # Just return with type for now.
                //->with('type')
                ->get();

                */

                $connection = Connections::where('delete_flag', 0)
                    ->whereIn('type', $connectionTypes)
                    ->where(function ($query) use ($path, $jumped) {
                        $query->where('source_system_id', $path[$jumped])
                            ->orWhere('target_system_id', $path[$jumped]);
                    })
                    ->where(function ($query) use ($path, $jumped) {
                        $query->where('source_system_id', $path[$jumped + 1])
                            ->orWhere('target_system_id', $path[$jumped + 1]);
                    })
                    ->with([
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
                        'systemTypeTarget',
                        'systemTypeSource',
                    ])
                    ->first();

                switch ($connection->type) {
                    case 1:
                        $connectionText = 'Gate';
                        break;
                    case 2:
                        $connectionText = 'WH';
                        break;
                    case 3:
                        $connectionText = 'JB';
                        break;

                    case 4:
                        $connectionText = 'Thera';
                        break;

                    case 5:
                        $connectionText = 'Poch';
                        break;
                    case 10:
                        $connectionText = 'Bridge';
                        break;
                    case 11:
                        $connectionText = 'Bridge';
                        break;
                }

                $text = $text . $system->name . ' (' . $connectionText . ') > ';

                $detailed_path[] = [
                    'jump' => $jumped,
                    'solar_system' => $system,
                    'connection' => $connection,
                ];
            } else {
                // Get the details for the final system.
                $text = $text . $system->name;

                $detailed_path[] = [
                    'jump' => $jumped,
                    'solar_system' => $system,
                    'connection' => null,
                ];
            }

            // Increase the jump count and move to next.
            $jumped++;
        }

        $settings = json_encode($request->all());
        // dd($settings);
        if (!$request->link) {
            $uuid = Str::uuid();

            $newSavedRoute = new SavedRoute();
            $newSavedRoute->user_id = FacadesAuth::id();
            $newSavedRoute->start_system_id = $request->start_system_id;
            $newSavedRoute->end_system_id = $request->finish_system_id;
            $newSavedRoute->settings = $settings;
            $newSavedRoute->saved = 0;
            $newSavedRoute->link = $uuid;
            $newSavedRoute->log_helper = 16;
            $newSavedRoute->save();
        } else {
            $uuid = $request->link;
        }

        $normalJumps = $this->normal_jumps(
            $jump_bridge,
            $aviod_connections,
            $titan_systems,
            $blops_systems,
            $avoid_systems,
            $start_system_id,
            $finish_system_id
        );


        $jumps_saved = $normalJumps - $total_jumps;

        $response = [
            'route' => $detailed_path,
            'jumps' => $total_jumps,
            'jumps_saved' => $jumps_saved,
            'time' => $time,
            'link' => $uuid,
            'text' => $text,
        ];

        return json_encode($response);
    }



    public function normal_jumps(
        $jump_bridge,
        $aviod_connections,
        $titan_systems,
        $blops_systems,
        $avoid_systems,
        $start_system_id,
        $finish_system_id
    ) {
        $startSystemTypeID  = SystemSystemType::where('system_id', $start_system_id)->first();
        $finishSystemTypeID  = SystemSystemType::where('system_id', $finish_system_id)->first();;


        $runStart = false;
        $runEnd = false;

        if (
            $startSystemTypeID->system_type_id == 7
            || $startSystemTypeID->system_type_id == 8
            || $startSystemTypeID->system_type_id == 9
        ) {


            $runStart = true;
        }
        if (
            $finishSystemTypeID->system_type_id == 7
            || $finishSystemTypeID->system_type_id == 8
            || $finishSystemTypeID->system_type_id == 9
        ) {
            $runEnd = true;
        }

        if ($runStart && $runEnd) {

            $path = [];
            $static_data = RoutingStaticData::where('id', 1)->first();
            $graph = json_decode($static_data->data, true);
            $bridges = null;

            if ($jump_bridge) {
                if (empty($aviod_connections)) {
                    $bridges = Connections::where('type', 3)
                        ->get();
                } else {
                    $bridges = Connections::where('type', 3)->whereNotIn('id', $aviod_connections)
                        ->get();
                }
            }

            if ($bridges) {
                foreach ($bridges as $bridge) {
                    /*
                30000001 => array:3 [
                    0 => 30000003
                    1 => 30000005
                    2 => 30000007
                ]
                */
                    array_push($graph[$bridge->source_system_id], $bridge->target_system_id);
                    array_push($graph[$bridge->target_system_id], $bridge->source_system_id);
                }
            }

            if ($titan_systems) {
                foreach ($titan_systems as $titan) {
                    $titan_connections = Connections::where('source_system_id', $titan)
                        ->where('type', 10)
                        ->where('delete_flag', 0)
                        ->get();

                    foreach ($titan_connections as $titan_edge) {
                        array_push($graph[$titan_edge->source_system_id], $titan_edge->target_system_id);
                    }
                }
            }

            if ($blops_systems) {
                // Oh goodie, we have a blops system to bridge from!
                // Lets get all those connections in

                foreach ($blops_systems as $blops) {
                    $blops_connections = Connections::where('source_system_id', $blops)
                        ->where('type', 11) // Type 10 is a blops, Type 11 is a Blops, 5ly or 10ly, you decide!
                        ->where('delete_flag', 0)
                        ->get();

                    foreach ($blops_connections as $blops_edge) {
                        array_push($graph[$blops_edge->source_system_id], $blops_edge->target_system_id);
                    }
                }
            }

            if ($avoid_systems) {
                foreach ($avoid_systems as $system) {
                    $graph[$system] = [];
                    $edge = Connections::where('source_system_id', $system)
                        ->where('type', 1)
                        ->where('delete_flag', 0)
                        ->get();

                    foreach ($edge as $node) {
                        $gates = Connections::where('source_system_id', $node->target_system_id)
                            ->where('type', 1)
                            ->where('delete_flag', 0)
                            ->where('target_system_id', '!=', $system)
                            ->get();
                        foreach ($gates as $build) {
                            $nodes[] = $build->target_system_id;
                        }
                        $graph[$node->target_system_id] = $nodes;
                        $nodes = [];
                    }
                }
            }



            foreach (findShortestPath($graph, $start_system_id, $finish_system_id, 100) as $n) {
                $path[] = $n;
            }

            $total_jumps = count($path);

            return $total_jumps;
        }
        return 0;
    }

    public function microtime_float()
    {
        [$usec, $sec] = explode(' ', microtime());

        return ((float) $usec + (float) $sec) * 1000;
    }
}
