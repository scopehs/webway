<?php

namespace App\Http\Controllers;

use App\Events\RouteUpdate;
use App\Models\SavedRoute;
use App\Models\SavedRouteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedRouteUserController extends Controller
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
        $routeID = SavedRoute::where('link', $request->link)->value('id');
        SavedRoute::where('link', $request->link)->update(['saved' => 1]);
        SavedRouteUser::create([
            'saved_route_id' => $routeID,
            'user_id' => Auth::id(),
        ]);

        $flag = collect([
            'flag' => 2,
            'user_id' => Auth::id(),
        ]);

        broadcast(new RouteUpdate($flag));
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

    public function getByUserID()
    {
        $routes = User::where('id', Auth::id())->select('id')->with(['savedRoutes', 'savedRoutes.startSystem', 'savedRoutes.endSystem'])->first();

        return ['routes' => $routes];
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
