<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\EVE;
use App\Models\EVE\Characters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class setCharacterWayPointController extends Controller
{
    public function set_way_point(Request $request)
    {



        $run = false;
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->id == $request->character_id) {
                $run = true;
            }
        }

        if ($run) {
            $character_id = $request->character_id;
            $system_id = $request->system_id;
            $add_to_beginning = $request->add_to_beginning;
            $clear_other_waypoints = $request->clear_other_waypoints;
            eveSetCharacterWaypoint($character_id, $system_id, $add_to_beginning, $clear_other_waypoints);

            // $response = EVE::setCharacterWaypoint($character_id, $system_id, $add_to_beginning, $clear_other_waypoints);
            // if ($response) {
            //     return response()->json([], 200); // Status code here
            // } else {
            //     return response()->json([$response], 400); // Status code here
            // }
        }
    }
}
