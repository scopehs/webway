<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\StatsHelper\StatsHelper;

class AllTheStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function allTheStatsUserCurrent()
    {
        $check = Auth::user();
        if ($check->can('view_stats')) {
            $stat = StatsHelper::allTheStatsCurrentAll();

            return ['stats' => $stat];
        } else {
            $stat = StatsHelper::allTheStatsCurrentUserID($check->id);

            return ['stats' => $stat];
        }
    }

    public function lastMonth()
    {
        $check = Auth::user();
        if ($check->can('view_stats')) {
            $stat = StatsHelper::allTheStatsLastAll();

            return ['stats' => $stat];
        } else {
            $stat = StatsHelper::allTheStatsLastUserID($check->id);

            return ['stats' => $stat];
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
