<?php

namespace App\Http\Controllers\ESI;

use App\Events\UserUpdate;
use App\Http\Controllers\Controller;
use App\Models\EVE\ESITokens;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ESITokensController extends Controller
{
    /**
     * ESIController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect the user to the Eve Online authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('eveonline')
            ->scopes([
                'publicData',
                'esi-location.read_location.v1',
                'esi-location.read_ship_type.v1',
                'esi-location.read_online.v1',
                'esi-fleets.read_fleet.v1',
                'esi-fleets.write_fleet.v1',
                'esi-ui.write_waypoint.v1',
            ])
            ->redirect();
    }

    /**
     * Obtain the user information from Eve Online.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {

        $user = Socialite::driver('eveonline')->user();
        $user_id = Auth::user()->id;

        $esiCheck = ESITokens::where('character_id', $user->id)->count();
        if ($esiCheck > 0) {
            $esi = ESITokens::where('character_id', $user->id)->first();
            $esi->character_id = $user->id;
            $esi->user_id = $user_id;
            $esi->name = $user->name;
            $esi->avatar = $user->avatar;
            $esi->token = $user->token;
            $esi->refresh_token = $user->refreshToken;
            $esi->owner_hash = $user->owner_hash;
            $esi->active = 1;
            $esi->log_helper = 27;
        } else {
            $esi = new ESITokens();
            $esi->character_id = $user->id;
            $esi->user_id = $user_id;
            $esi->name = $user->name;
            $esi->avatar = $user->avatar;
            $esi->token = $user->token;
            $esi->refresh_token = $user->refreshToken;
            $esi->owner_hash = $user->owner_hash;
            $esi->active = 1;
            $esi->log_helper = 27;
        }

        $esi->save();



        eveGetAndUpdateCharacter($user->id);
        // If the character is in an alliance, get and update the alliance.
        $character = eveGetCharacter($user->id);
        isset($character['alliance_id']) ? eveGetAndUpdateAlliance($character['alliance_id']) : null;
        // Characters::where('id', $user->id)->update(['user_id' => Auth::id()]);
        $flag = collect([
            'flag' => 1,
            'user_id' => $user_id,
        ]);

        broadcast(new UserUpdate($flag));

        return redirect('/characters');
    }
}
