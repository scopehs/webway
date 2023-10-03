<?php

namespace App\Http\Controllers;

use App\Models\SDE\Constellation;
use App\Models\SDE\Region;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list()
    {
        $systemlist = SolarSystem::get(['system_id as value', 'name as text']);

        return ['systemlist' => $systemlist];
    }

    public function titanList()
    {
        $titanList = SolarSystem::whereHas('systemType', function (Builder $query) {
            $query->where('system_types.id', 8)
                ->orWhere('system_types.id', 9);
        })->get(['system_id as value', 'name as text']);

        return ['titanList' => $titanList];
    }

    public function regionList()
    {
        $systemlist = Region::get(['region_id as value', 'name as text']);

        return ['regionlist' => $systemlist];
    }

    public function ConstellationList()
    {
        $systemlist = Constellation::get(['constellation_id as value', 'name as text']);

        return ['constellationlist' => $systemlist];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
