<?php

namespace App\Http\Controllers;

use App\Events\CharLocationUpdate;
use App\Models\CharTracking;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\StatsHelper\StatsHelper;

class CharacterController extends Controller
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
    }

    public function enterTracking($id)
    {
        $checkChar = Characters::where('id', $id)->first();
        if ($checkChar->user_id != Auth::id()) {
            return null;
        }
        Characters::where('id', $id)->update(['current_system_id' => null, 'last_system_id' => null]);
    }

    public function tracking(Request $request, $id)
    {
        $checkChar = Characters::where('id', $id)->first();
        if ($checkChar->user_id != Auth::id()) {
            return 'get out';
        }

        if ($request->tracking == 1) {
            $min30 = now()->subHours(5);
            CharTracking::where('character_id', $id)->where('created_at', '<=', $min30)->delete();

            $routes = CharTracking::where('character_id', $id)->get();
            $i = 1;
            foreach ($routes as $route) {
                $route->update(['count' => $i]);
                $i++;
            }

            $flag = collect([
                'flag' => 8,
                'char_id' => $id,
            ]);
            broadcast(new CharLocationUpdate($flag));
            $flag = collect([
                'flag' => 6,
                'char_id' => $id,
            ]);
            broadcast(new CharLocationUpdate($flag));
        } else {
            CharTracking::where('character_id', $id)->delete();
            $flag = collect([
                'flag' => 7,
                'char_id' => $id,
            ]);
            broadcast(new CharLocationUpdate($flag));
        }

        Characters::where('id', $id)->update(['current_system_id' => null, 'last_system_id' => null]);

        ESITokens::where('character_id', $id)->update(['tracking' => $request->tracking]);
        $char = Characters::where('id', $id)->first();
        $char->tracking = $request->tracking;
        $char->save();

        StatsHelper::allTheStatsBcastSoloID(Auth::id());
    }

    public function list()
    {
        $charlist = ESITokens::where('user_id', Auth::id())->where('active', 1)->get(['character_id as value', 'name as text', 'avatar as url']);

        return ['charlist' => $charlist];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ['selectedChar' => Characters::where('id', $id)->first()];
    }

    public function getAllByUserId($id)
    {
        if (Auth::id() == $id) {
            $causerID = Auth::id();

            $now = Carbon::now();
            $count = UserActivity::where('user_id', $causerID)->where('done', 0)->first();
            if (! $count) {
                UserActivity::create([
                    'user_id' => $causerID,
                    'year' => $now->year,
                    'month' => $now->month,
                    'week' => $now->weekOfYear,
                    'day' => $now->day,
                ]);
                $causerID = Auth::id();
            }
            $user = User::where('id', $causerID)->first();
            $user->online = 1;
            $user->save();

            return ['chars' => Characters::where('user_id', $id)->with(['currentSystem', 'lastSystem', 'esiChar'])->get()];
        } else {
            return null;
        }
    }

    /**
     * Update the spfffecified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     Characters::where('id', $id)->update($request->all());
    // }

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
