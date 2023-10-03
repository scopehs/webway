<?php

namespace App\Http\Controllers;

use App\Events\RouteUpdate;
use App\Models\SavedRoute;
use App\Models\SavedRouteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedRouteController extends Controller
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

    public function getRouteLink($link)
    {
        $data = SavedRoute::where('link', $link)->with(['startSystem', 'endSystem'])->first();
        // dd($data);

        return $data;
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

    public function loadRoute(Request $request)
    {
        $flag = collect([
            'flag' => 1,
            'link' => $request->link,
            'user_id' => Auth::id(),
        ]);

        broadcast(new RouteUpdate($flag));
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
    public function destroy(Request $request)
    {
        $route = SavedRoute::where('link', $request->link)->first();
        SavedRouteUser::where('user_id', Auth::id())->where('saved_route_id', $route->id)->delete();

        $check = SavedRouteUser::where('saved_route_id', $route->id)->count();
        if ($check == 0) {
            SavedRoute::where('link', $request->id)->delete();
        }

        $flag = collect([
            'flag' => 2,
            'user_id' => Auth::id(),
        ]);

        broadcast(new RouteUpdate($flag));
    }
}
