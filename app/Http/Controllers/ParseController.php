<?php

namespace App\Http\Controllers;

use App\Events\MappingUpdate;
use App\Jobs\SigRouteJob;
use App\Models\EVE\Characters;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Gasstationhelper\Gasstationhelper;
use utils\Helper\Helper;
use utils\StatsHelper\StatsHelper;

class ParseController extends Controller
{
    /**
     * @OA\Post(
     *      path="/signatures/post",
     *      operationId="createOrUpdate",
     *      tags={"Parse"},
     *      summary="Parse EVE Signature Window",
     *      description="Paste a signature window paste and parse and insert in database.",
     *   @OA\RequestBody(
     *       description="List of user object",
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="array",
     *               @OA\Items(
     *                      @OA\Property(
     *                      property="paste",
     *                      type="string",
     *                      example="HIK-016 Cosmic Signature Relic Site Ruined Blood Raider Monument Site 100.0% 28.76 AU"
     *                      ),
     *                      @OA\Property(
     *                      property="system_id",
     *                      type="integer",
     *                      example="1234567"
     *                      ),
     *              )
     *           )
     *       )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function post(Request $request)
    {
        $run = false;
        $endpoint = '/api/signature/post';

        $paste = $request->input('paste');
        $system_id = $request->system_id;

        $checkLocations = Characters::where('user_id', Auth::id())->where('tracking', '>=', 1)->get();
        foreach ($checkLocations as $var) {
            if ($var->current_system_id == $system_id) {
                $run = true;
            }
        }

        if (!$run) {
            return null;
        }

        //$user_id = $request->input('user_id');

        if (empty($paste)) {
            //return response()->json("Bad Request", 400);
            return null;
        }

        if (empty($system_id)) {
            //return response()->json("Bad Request", 400);
            return null;
        }

        // Check system exists

        $system = SolarSystem::where('system_id', $system_id)->first();
        // $system = SolarSystem::where('system_id', 30004025)->first();

        if (empty($system)) {
            //return response()->json("Bad Request", 400);
            return null;
        }

        /*
        ENB-520\tCosmic Signature\tRelic Site\t\t40.8%\t32.91 AU\n
        HIK-016\tCosmic Signature\tRelic Site\tRuined Blood Raider Monument Site\t100.0%\t28.76 AU\n
        */

        $sigs = explode("\n", $paste);

        /*
        array:2 [
          0 => "ENB-520\tCosmic Signature\tRelic Site\t\t40.8%\t32.91 AU"
          1 => "HIK-016\tCosmic Signature\tRelic Site\tRuined Blood Raider Monument Site\t100.0%\t28.76 AU"
        ]
        */

        // Set current time/date

        $now = Carbon::now();
        $later = Carbon::now()->addHours(24);
        // dd(Auth::user());
        foreach ($sigs as $sig) {
            $exploded = explode("\t", $sig);

            /*
            [
            "FBH-845",
            "Cosmic Anomaly",
            "Combat Site",
            "Blood Raider Forlorn Den",
            "100.0%",
            "38.41 AU"
            ]

            # SID ID                - signature_id (string)
            # TYPE                  - type (string) (index) (nullable)
            # GROUP                 - group (string)
            # NAME                  - name (string) (nullable)
            # SIGNAL STR
            # DISTANCE WHEN SCANNED
            */

            $name_id = $exploded[0];
            $signature_id = explode('-', $exploded[0]);
            $type = $exploded[1];
            $group = $exploded[2];
            $name = $exploded[3];
            if ($name == '') {
                $name = null;
            }
            $signal_strength = $exploded[4];

            // Add to database.
            // Send to another control to check & update database..
            // Do it here for now.

            // Everything that is not in the signature window, ignore, we don't care about these. i.e ships/structures/anoms
            /*
            AJO-298	Cosmic Anomaly	Combat Site	Drone Horde	100.0%	42.35 AU
            WHA-896	Deployable			3.7%	52.20 AU
            MYM-980	Cosmic Signature	Combat Site		28.1%	45.88 AU
            KNJ-488	Structure	Engineering Complex	Raitaru	91.6%	47.47 AU
            HQI-255	Ship			0.3%	4.24 AU
            DWD-940	Drone			1.5%	48.53 AU
            */

            if ($type == 'Cosmic Signature') {
                $newCompltedName = false;

                //changes the group text to the database id
                switch ($group) {

                    case 'Wormhole':
                        $group = 1;
                        break;

                    case 'Relic Site':
                        $group = 2;
                        // $life = now()->addHours(12);
                        break;

                    case 'Data Site':
                        $group = 3;
                        // $life = now()->addHours(12);
                        break;

                    case 'Gas Site':
                        $group = 4;
                        // $life = now()->addHours(12);
                        break;

                    case 'Combat Site':
                        $group = 5;
                        // $life = now()->addHours(12);
                        break;

                    case 'Ore Site':
                        $group = 6;
                        // $life = now()->addHours(12);
                        break;

                    case '':
                        $group = 7;
                        // $life = now()->addHours(2);
                        break;

                    default:
                        $group = 7;
                        break;
                }
                // Check if sig already exists
                $signature = Signature::where('name_id', $name_id)
                    ->where('system_id', $system_id)
                    ->where('delete', 0)
                    ->first();

                if ($signature) {
                    $signature->name_id = $name_id;
                    $signature->signature_id = $signature_id[0];

                    if ($signature->signal_strength != 100.00) {
                        if ($signature->signature_group_id == 7) {
                            $signature->signature_group_id = $group;
                        }
                        if ($name != null && $signature->name == null) {
                            $newCompltedName = true;
                        }
                        $signature->signal_strength = $signal_strength;
                    }
                    $signature->bookmark_syntax = 'Bookmark: ' . $signature_id[0] . ' - ' . $group; // Placeholder
                    $signature->modified_by_id = Auth::id();
                    $signature->modified_by_name = Auth::user()->name;
                    $signature->log_helper = 20;
                } else {
                    if ($name != null) {
                        $newCompltedName = true;
                    }

                    $signature = new Signature();
                    $signature->name_id = $name_id;
                    $signature->signature_id = $signature_id[0];
                    $signature->system_id = $system->system_id;
                    $signature->signature_group_id = $group; // Wormhole / Gas
                    $signature->signal_strength = $signal_strength;
                    $signature->bookmark_syntax = 'Bookmark: ' . $signature_id[0] . ' - ' . $group; // Confirm pathfinder Syntax
                    $signature->life_time = $now;
                    $signature->life_left = $later;
                    $signature->created_by_id = Auth::id();
                    $signature->created_by_name = Auth::user()->name;
                    $signature->delete = 0;
                    $signature->modified_by_id = Auth::id();
                    $signature->modified_by_name = Auth::user()->name;
                    $signature->log_helper = 4;
                }

                if ($newCompltedName && $signature->completed_by_id == null) {
                    if ($signature->signature_group_id != 1) {
                        $signature->completed_by_id = Auth::id();
                        $signature->completed_by_name = Auth::user()->name;
                        $signature->name = $name;
                        $signature->save();
                        $id = $signature->id;
                        SigRouteJob::dispatch($id)->onQueue('slow');
                    }

                    // if ($signature->signature_group_id == 1 && $signal_strength == 100) {
                    //     $signature->completed_by_id = Auth::id();
                    //     $signature->completed_by_name = Auth::user()->name;
                    //     $signature->name = $name;
                    //     $signature->save();
                    //     $id = $signature->id;
                    // }

                    if ($signature->signature_group_id == 1 && $signal_strength != 100) {
                        $signature->save();
                        $id = $signature->id;
                    }
                } else {
                    $signature->save();
                }

                if ($newCompltedName) {
                    Gasstationhelper::ping($signature->id);
                }

                //# Send Broadcast Here
                $message = Helper::trackingSig($signature->id);
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'system_id' => $system_id,
                ]);
                broadcast(new MappingUpdate($flag));
            }
        }

        if ($paste) {
            StatsHelper::allTheStatsBcastSoloID(Auth::id());

            return response()->json(200);
        } else {
            //return response()->json("Resource Not Found", 404);
            return null;
        }

        //# Write the loop at the end.
    }
}
