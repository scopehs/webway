<?php

namespace App\Http\Controllers;

use App\Models\CharTracking;
use App\Models\EVE\Characters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

class SolarSystemController extends Controller
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

    public function charCount($id)
    {
        $run = false;
        $chars = Characters::where('tracking', '>=', 1)->where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            $routes = CharTracking::where('character_id', $char->id)->get();
            foreach ($routes as $route) {
                if ($route->current_system_id = $id || $route->last_system_id = $id) {
                    $run = true;
                }

                if ($run = true) {
                    break;
                }
            }
            if ($run = true) {
                break;
            }
        }

        if (! $run) {
            return null;
        }
        $data = Helper::systemChars($id);

        return ['systemUsers' => $data];
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
