<?php

namespace App\Http\Controllers;

use App\Events\HotAreaUpdate;
use App\Models\HotArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class HotAreaController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('view_hot_area')) {
            $data = HotArea::with(['system', 'constellation', 'region'])->get();

            return ['hot' => $data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('view_hot_area')) {
            $new = new HotArea();
            $new->system_id = $request->system_id ?? null;
            $new->region_id = $request->region_id ?? null;
            $new->constellation_id = $request->constellation_id ?? null;
            $new->log_helper = 29;
            $new->save();

            $message = HotArea::where('id', $new->id)->with(['system', 'constellation', 'region'])->first();
            $flag = collect([
                'flag' => 1,
                'message' => $message,
            ]);
            broadcast(new HotAreaUpdate($flag));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if (Auth::user()->can('view_hot_area')) {
            HotArea::where('id', $id)->update(['log_helper' => 30]);
            $hot = HotArea::where('id', $id)->first();
            $hot->delete();
        }
    }
}
