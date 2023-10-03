<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Connections\Connections;
use App\Models\SDE\SolarSystem;
use Illuminate\Http\Request;

class importGoonJumpBridges extends Controller
{
    public function store(Request $request)
    {
        // Import Jump Bridge Network

        $bridges = $request->data;

        if (! $bridges) {
            return null;
        }

        Connections::where('type', 3)->delete();

        /*
        // Region	System / POS	System / POS	Status	Owner	Password	Dist (ly)	Route	Friendly
        // Delve	7UTB-F @ 1-1	PS-94K @ 2-1	Online	CONDI	-	0.75	DodgerBlue	Yes
        // Delve	4K-TRB @ 2-1	1-SMEB @ 5-1	Online	CONDI	-	2.66	DodgerBlue	Yes
        */

        $lines = explode("\n", $bridges);

        $pattern = "/\bRegion\b/";

        foreach ($lines as $line) {
            // Ignore Headers
            // Region	System / POS	System / POS	Status	Owner	Password	Dist (ly)	Route	Friendly
            if (! preg_match($pattern, $line)) {

                // Import Valid Bridges Only.
                // Delve	7UTB-F @ 1-1	PS-94K @ 2-1	Online	CONDI	-	0.75	DodgerBlue	Yes
                $bridge = explode("\t", $line);
                if (count($bridge) != 9) {
                    return null;
                }

                /*
                array:9 [
                0 => "Delve"                = Region
                1 => "7UTB-F @ 1-1"         = System
                2 => "PS-94K @ 2-1"         = System
                3 => "Online"               = Status
                4 => "CONDI"                = Owner
                5 => "-"                    = Password
                6 => "0.75"                 = Distance
                7 => "DodgerBlue"           = Route
                8 => "Yes"                  = Friendly
                ]
                */

                /*
                $table->foreignId('to_system_id');
                $table->foreignId('from_system_id');
                $table->boolean('online'); # True = Online
                $table->foreignId('alliance_id');
                $table->string('password');
                $table->float('distance_ly', 5, 2);
                $table->string('route');
                $table->boolean('friendly'); # True = Friendly
                */

                $to_system_id = explode(' @ ', $bridge[1]);
                $from_system_id = explode(' @ ', $bridge[2]);

                $to_system = SolarSystem::where('name', $to_system_id[0])
                    ->first();

                $from_system = SolarSystem::where('name', $from_system_id[0])
                    ->first();

                $connection = new Connections();

                $connection->source_system_id = $to_system->system_id;
                $connection->target_system_id = $from_system->system_id;
                $connection->type = 3;
                $connection->delete_flag = 0;
                $connection->log_helper = 21;
                $connection->save();
            }
        }

        return true;
    }
}
