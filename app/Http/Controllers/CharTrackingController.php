<?php

namespace App\Http\Controllers;

use App\Events\CharLocationUpdate;
use App\Events\MappingUpdate;
use App\Models\CharTracking;
use App\Models\EVE\Characters;
use App\Models\SDE\SolarSystem;
use App\Models\SigNote;
use App\Models\SystemNote;
use App\Models\Zkill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

class CharTrackingController extends Controller
{
    /**
     * Display a listing of 456the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    // public function test()
    // {
    //     Artisan::call('chffffaracter:location');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $checkChar = Characters::where('id', $request->character_id)->first();
    //     if ($checkChar->user_id != Auth::id()) {
    //         return null;
    //     }
    //     $system_id = $request->current_system_id;
    //     $last_system = $request->last_system_id;

    //     $flag = collect([
    //         'char_id' => $request->character_id,
    //         'flag' => 2,
    //         'current_system_id' => $system_id,
    //         'last_system_id' => $last_system
    //     ]);
    //     Broadcast(new CharLocationUpdate($flag));

    //     $message = SolarSystem::where('system_id', $system_id)->get(['system_id as value', 'name as text']);
    //     $flag = collect([
    //         'char_id' => $request->character_id,
    //         'flag' => 3,
    //         'message' => $message
    //     ]);
    //     Broadcast(new CharLocationUpdate($flag));

    //     $lastSystemRecord = CharTracking::where('character_id', $request->character_id)->latest()->first();

    //     if ($lastSystemRecord) {
    //         if ($system_id == $lastSystemRecord->current_system_id) {
    //             $new = $lastSystemRecord;
    //         } else {

    //             $new =  CharTracking::create($request->all());
    //         }
    //     } else {
    //         $new =  CharTracking::create($request->all());
    //     }

    //     // $new =  CharTracking::create($request->all());

    //     Characters::where('id', $request->character_id)
    //         ->update([
    //             'current_system_id' => $request->current_system_id, 'last_system_id' => $request->last_system_id
    //         ]);

    //     $messages = CharTracking::where('id', $new->id)->with([
    //         'currentSystem.systemType',
    //         'currentSystem.region',
    //         'lastSystem.systemType',
    //         'lastSystem.region',
    //         'currentSystem.statics.type',
    //         'lastSystem.statics.type',
    //         'currentSystem.jove',
    //         'lastSystem.jove',
    //         'currentSystem.effect',
    //         'lastSystem.effect',
    //         'ship',
    //         'character'
    //     ])->first();
    //     // foreach ($messages as $message) {
    //     $flag = collect([
    //         'flag' => 4,
    //         'char_id' => $request->character_id,
    //         'message' => $messages,
    //         'system_id' => $system_id
    //     ]);
    //     broadcast(new CharLocationUpdate($flag));

    //     $message = Helper::systemChars($request->current_system_id);
    //     $flag = collect([
    //         'flag' => 4,
    //         'message' => $message,
    //         'system_id' => $request->current_system_id
    //     ]);
    //     broadcast(new MappingUpdate($flag))->toOthers();

    //     $message = Helper::systemChars($request->last_system_id);
    //     $flag = collect([
    //         'flag' => 4,
    //         'message' => $message,
    //         'system_id' => $request->last_system_id
    //     ]);
    //     broadcast(new MappingUpdate($flag))->toOthers();

    //     // }
    // }

    /**
     * Display the spfffecified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function mapping($charID)
    {
        $run = false;
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->id == $charID) {
                $run = true;
            }
        }

        if (!$run) {
            return null;
        }

        $nodes = [];
        $links = [];
        $currentLocation = Characters::where('id', $charID)->value('current_system_id');
        $routes = CharTracking::where('character_id', $charID)->get();
        $current = $routes->pluck('current_system_id');
        $last = $routes->pluck('last_system_id');
        $allSystems = $current->merge($last);
        $allSystems = $allSystems->unique();
        $allSystems = $allSystems->filter()->all();
        $count = 0;
        foreach ($allSystems as $systemID) {
            $systemName = SolarSystem::where('system_id', $systemID)->value('name');
            $data = [];
            if ($currentLocation == $systemID) {
                $data = [
                    "n" . $systemID => [
                        'name' => $systemName,
                        'current' => true,
                        'id' => "n" . $systemID,
                    ]
                ];
            } else {
                $data = [
                    "n" . $systemID => [
                        'name' => $systemName,
                        'current' => false,
                        'id' => "n" . $systemID,
                    ]
                ];
            }

            array_push($nodes, $data);

            $lastSystemID = CharTracking::where('character_id', $charID)
                ->where('current_system_id', $systemID)
                ->whereNotNull('last_system_id')->value('last_system_id');
            if ($lastSystemID) {
                $count++;
                $data1 = [];
                $data1 = [
                    "edge" . $count => [
                        'source' => "n" . $systemID,
                        'target' => "n" . $lastSystemID,
                    ]

                ];

                array_push($links, $data1);
            }
        }

        return [
            'nodes' => $nodes,
            'links' => $links,
        ];
    }

    public function getTrackingInfo($charid)
    {
        $user = Auth::id();
        $run = false;
        $checkChars = Characters::where('user_id', $user)->get();
        foreach ($checkChars as $checkChar) {
            if ($checkChar->id == $charid) {
                $run = true;
            }
        }
        if ($run) {
            $char = Characters::where('id', $charid)->first();
            $currentSystemID = $char->current_system_id;
            $lastSystemID = $char->last_system_id;

            $route = CharTracking::where('character_id', $charid)
                ->with([
                    'currentSystem.systemType:id,name,name_full',
                    'currentSystem.region',
                    'currentSystem.statics.type',
                    'currentSystem.jove',
                    'currentSystem.effect',
                    'currentSystem.shattered',
                    'lastSystem.systemType:id,name,name_full',
                    'lastSystem.region',
                    'lastSystem.statics.type',
                    'lastSystem.jove',
                    'lastSystem.effect',
                    'lastSystem.shattered',
                    'ship:typeID,typeName',
                    'character:id,name,user_id',
                ])
                ->get();

            $count = $route->count();
            if ($count > 0 && !$currentSystemID) {
                $temp = CharTracking::where('character_id', $charid)->latest()->first();
                $currentSystemID = $temp->current_system_id;
                activity()->disableLogging();
                Characters::where('id', $charid)->update(['current_system_id' => $currentSystemID]);
                activity()->enableLogging();
            }

            if ($count > 0 && !$lastSystemID) {
                $temp = CharTracking::where('character_id', $charid)->latest()->first();
                $lastSystemID = $temp->last_system_id;
                activity()->disableLogging();
                Characters::where('id', $charid)->update(['last_system_id' => $lastSystemID]);
                activity()->enableLogging();
            }

            if ($lastSystemID > 0) {
                $lastSystemUsers = Helper::systemChars($lastSystemID);
                $lastSystemSigs = Helper::allSig($lastSystemID);
                $lastSystemNotes = SystemNote::where(
                    'system_id',
                    $lastSystemID
                )->with(['user:id,name'])
                    ->get();
                $lastSystemSigNotes = SigNote::where('system_id', $lastSystemID)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();

                $lastSystemKills = Zkill::where('solar_system_id', $lastSystemID)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();

                $message = Helper::systemChars($lastSystemID);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $lastSystemID,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            if ($currentSystemID > 0) {
                $currentSystemUsers = Helper::systemChars($currentSystemID);
                $currentSystemSigs = Helper::allSig($currentSystemID);
                $currentSystemNotes = SystemNote::where('system_id', $currentSystemID)
                    ->with(['user:id,name'])
                    ->get();
                $currentSystemSigNotes = SigNote::where('system_id', $currentSystemID)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();
                $currentSystemKills = Zkill::where('solar_system_id', $currentSystemID)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();
                $wormHoleTypeList = Helper::wormHoleTypeList($currentSystemID);

                $message = Helper::systemChars($currentSystemID);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $currentSystemID,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            return [
                'route' => $route,
                'lastSystemSigs' => $lastSystemSigs ?? null,
                'lastSystemNotes' => $lastSystemNotes ?? null,
                'lastSystemUsers' => $lastSystemUsers ?? null,
                'lastSystemSigNotes' => $lastSystemSigNotes ?? null,
                'lastSystemKills' => $lastSystemKills ?? 0,
                'lastSystemID' => $lastSystemID ?? null,
                'currentSystemSigs' => $currentSystemSigs ?? null,
                'currentSystemUsers' => $currentSystemUsers ?? null,
                'currentSystemNotes' => $currentSystemNotes ?? null,
                'currentSystemSigNotes' => $currentSystemSigNotes ?? null,
                'currentSystemKills' => $currentSystemKills ?? 0,
                'currentSystemID' => $currentSystemID ?? null,
                'count' => $count,
                'wormholeTypeList' => $wormHoleTypeList ?? null,
            ];
        }
    }

    public function getTrackingInfoByTrackID($trackID)
    {
        $user = Auth::id();
        $run = false;
        $checkChars = Characters::where('user_id', $user)->get();
        foreach ($checkChars as $checkChar) {
            $tracks = CharTracking::where('character_id', $checkChar->id)->where('id', $trackID)->get();
            if ($tracks) {
                $run = true;
            }
        }
        if ($run) {
            $track = CharTracking::where('id', $trackID)->first();

            if ($track->last_system_id) {
                $lastSystemUsers = Helper::systemChars($track->last_system_id);
                $lastSystemSigs = Helper::allSig($track->last_system_id);
                $lastSystemNotes = SystemNote::where(
                    'system_id',
                    $track->last_system_id
                )->with(['user:id,name'])
                    ->get();
                $lastSystemSigNotes = SigNote::where('system_id', $track->last_system_id)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();

                $lastSystemKills = Zkill::where('solar_system_id', $track->last_system_id)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();

                $message = Helper::systemChars($track->last_system_id);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $track->last_system_id,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            if ($track->current_system_id) {
                $currentSystemUsers = Helper::systemChars($track->current_system_id);
                $currentSystemSigs = Helper::allSig($track->current_system_id);
                $currentSystemNotes = SystemNote::where('system_id', $track->current_system_id)
                    ->with(['user:id,name'])
                    ->get();
                $currentSystemSigNotes = SigNote::where('system_id', $track->current_system_id)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();
                $currentSystemKills = Zkill::where('solar_system_id', $track->current_system_id)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();

                $message = Helper::systemChars($track->current_system_id);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $track->current_system_id,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            return [
                'lastSystemSigs' => $lastSystemSigs ?? null,
                'lastSystemNotes' => $lastSystemNotes ?? null,
                'lastSystemUsers' => $lastSystemUsers ?? null,
                'lastSystemSigNotes' => $lastSystemSigNotes ?? null,
                'lastSystemKills' => $lastSystemKills ?? 0,
                'lastSystemID' => $track->last_system_id ?? null,
                'currentSystemSigs' => $currentSystemSigs ?? null,
                'currentSystemUsers' => $currentSystemUsers ?? null,
                'currentSystemNotes' => $currentSystemNotes ?? null,
                'currentSystemSigNotes' => $currentSystemSigNotes ?? null,
                'currentSystemKills' => $currentSystemKills ?? 0,
                'currentSystemID' => $track->current_system_id ?? null,
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
