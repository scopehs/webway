<?php

namespace App\Http\Controllers;

use App\Events\AllConnectionsUpdate;
use App\Events\MappingUpdate;
use App\Events\WhaleUpdate;
use App\Models\CharTracking;
use App\Models\EVE\Characters;
use App\Models\JoveSystems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class JoveSystemController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * Display a listing of the and lots of other thingseefwefwefwefwe resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function systemsWithDrifter()
    {
        if (Auth::user()->can('view_whalers')) {
            $data = whaleNumbersAll();

            return ['systems' => $data];
        }
    }

    public function noDrfiterUpdateMain($id)
    {

        $jov = JoveSystems::where('system_id', $id)->first();
        $jov->last_updated = now();
        $jov->updated_by = Auth::id();
        $jov->log_helper = 52;
        $jov->save();
        $message = whaleNumbersSoloSystemID($id);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
        ]);
        broadcast(new WhaleUpdate($flag));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $run = false;
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->current_system_id == $request->system_id) {
                $run = true;
            }
        }
        if (!$run) {
            return null;
        }
        $new = new JoveSystems();
        $new->drifter = $request->drifter;
        $new->system_id = $request->system_id;
        $new->log_helper = 22;
        $new->save();

        $messages = CharTracking::where('id', $id)->with([
            'currentSystem.systemType',
            'currentSystem.region',
            'lastSystem.systemType',
            'lastSystem.region',
            'currentSystem.statics.type',
            'lastSystem.statics.type',
            'currentSystem.jove',
            'lastSystem.jove',
            'currentSystem.effect',
            'lastSystem.effect',
            'ship',
            'character',
        ])->first();
        // foreach ($messages as $message) {
        $flag = collect([
            'flag' => 3,
            'message' => $messages,
            'system_id' => $messages->current_system_id,
        ]);
        broadcast(new MappingUpdate($flag));
        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function regionList()
    {
        if (Auth::user()->can('view_whalers')) {
            $list = [];
            $regions = JoveSystems::where('drifter', 1)->with(['system.region'])->get();
            foreach ($regions as $region) {
                $data = [];
                $data = [
                    'text' => $region->system->region->name,
                    'value' => $region->system->region->region_id,
                ];
                if (!in_array($data, $list)) {
                    array_push($list, $data);
                }
            }

            array_multisort(array_column($list, 'text'), SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE, $list);

            return ['regions' => $list];
        }
    }

    public function lastChecked($id)
    {
        if (Auth::user()->can('view_whalers')) {
            $jov = JoveSystems::where('id', $id)->first();
            $jov->last_updated = now();
            $jov->updated_by = Auth::id();
            $jov->log_helper = 52;
            $jov->save();
            $message = whaleNumbersSolo($id);
            $flag = collect([
                'flag' => 1,
                'message' => $message,
            ]);
            broadcast(new WhaleUpdate($flag));
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
