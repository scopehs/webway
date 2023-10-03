<?php

namespace App\Http\Controllers;

use App\Events\AllConnectionsUpdate;
use App\Events\MappingUpdate;
use App\Events\RouteUpdate;
use App\Models\ConnectionPayment;
use App\Models\Connections\Connections;
use App\Models\EVE\Characters;
use App\Models\Scanning\Signature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;
use utils\RouteHelper\RouteHelper;
use utils\StatsHelper\StatsHelper;

class ParseShowInfoWindowController extends Controller
{
    public function parse_info(Request $request, $sigID)
    {
        $run = true;
        $sigCheck = Signature::where('id', $sigID)->first();
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->current_system_id == $sigCheck->system_id) {
                $run = true;
            }
        }

        if (!$run) {
            return null;
        }

        /*
        An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere.

        This wormhole seems to lead into unknown parts of space.

        This wormhole is reaching the end of its natural lifetime.
        This wormhole has not yet had its stability significantly disrupted by ships passing through it.
        Larger ships can pass through this wormhole.
        */

        /*
         * Line 1 : Ignore
         * Line 2 : Ignore
         * Line 3 : This wormhole seems to lead into {important part} parts of space.
         * Line 4 : Ignore
         * Line 5 :
         *         This wormhole has {not yet begun} its natural cycle of decay and should last at least another day
         *         This wormhole is {beginning to decay}, and probably won't last another day
         *         This wormhole is {reaching the end} of its natural lifetime
         * Line 6 :
         *         This wormhole has {not yet} had its stability significantly disrupted by ships passing through it
         *         This wormhole has had its stability reduced by ships passing through it, but {not to a critical degree} yet
         *         This wormhole has had its {stability critically disrupted} by the mass of numerous ships passing through and is on the verge of collapse
         * Line 7 :
         *         Very large ships can pass through this wormhole              = Carriers =>
         *         Larger ships can pass through this wormhole                  = Battleship =>
         *         Up to medium size ships can pass through this wormhole       = Battlecrusers =>
         *         Only the smallest ships can pass through this wormhole       = Destroyer =>
         *
         * Line 3   : System Type                                               = {important part}
         *
         *          : Unknown                                                   = C1/C2/C3
         *          : Dangerous Unknown                                         = C4/C5
         *          : Deadly Unknown                                            = C6
         *          : High Security                                             = HS
         *          : Low Security                                              = LS
         *          : Null Security                                             = NS
         *          : Triglavian Space                                          = Pochven
         *
         * Line 5   : Life
         *
         *          : {not yet begun}                                           = more than 24 hours        (Fresh)
         *          : {beginning to decay}                                      = between 4 and 24 hours    (Fresh)
         *          : {reaching the end}                                        = less than 4 hours         (EOL)
         *
         * Line 6   : Mass
         *
         *          : {not yet}                                                 = over 50%                  (Fresh)
         *          : {not to a critical degree}                                = between 50% and 10%       (Shrink)
         *          : {stability critically disrupted}                          = less than 10%             (Crit)
         *
         * Line 7   : Ship Size
         *
         *          : {Very large ships can pass through this wormhole}         = All ships except for Titans and supercarriers can pass through this hole
         *          : {Larger ships can pass through this wormhole}             = Battleships, Orcas, and smaller ships can pass through this hole
         *          : {Up to medium size ships can pass through this wormhole}  = Unplated Nestors, battlecruisers and smaller ships can pass through this hole
         *          : {Only the smallest ships can pass through this wormhole}  = Only frigates, destroyers, or specially fit HICs can pass through this hole
         *
         *
         *
        */

        $text = $request->text;

        if (!$text) {
            return response()->json('Resource Not Found', 404);
        }

        $lines = explode("\n", $text);

        if (count($lines) != 7) {
            return response()->json('Resource Not Found', 404);
        }

        /*
        array:7 [
                0 => "An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere."
                1 => ""
                2 => "This wormhole seems to lead into unknown parts of space."
                3 => ""
                4 => "This wormhole is reaching the end of its natural lifetime."
                5 => "This wormhole has not yet had its stability significantly disrupted by ships passing through it."
                6 => "Larger ships can pass through this wormhole."
        ]
        */

        $system_type = $lines[2];
        $life = $lines[4];
        $mass = $lines[5];
        $ship_size = $lines[6];

        /* System Type */
        $pattern = [
            "/\bunknown\b/"               => 1,
            "/\bdangerous unknown\b/"     => 2,
            "/\bdeadly unknown\b/"        => 3,
            "/\bhigh security\b/"         => 4,
            "/\blow security\b/"          => 5,
            "/\bnull security\b/"         => 6,
            "/\bTriglavian space\b/"      => 7,
            "/\bThera system\b/"          => 8,
        ];
        foreach ($pattern as $key => $type) {
            if (preg_match($key, $system_type)) {
                $wormhole_details['leads_to'] = $type;
            }
        }

        /* Life */
        $pattern = [
            "/\bnot yet begun\b/"         => 1,
            "/\bbeginning to decay\b/"    => 2,
            "/\breaching the end\b/"      => 3,
        ];
        foreach ($pattern as $key => $type) {
            if (preg_match($key, $life)) {
                $wormhole_details['life'] = $type;
            }
        }

        /* Mass */
        $pattern = [
            "/\bnot yet\b/"                             => 1,
            "/\bnot to a critical degree\b/"            => 2,
            "/\bstability critically disrupted\b/"      => 3,
        ];
        foreach ($pattern as $key => $type) {
            if (preg_match($key, $mass)) {
                $wormhole_details['mass'] = $type;
            }
        }

        /* Ship Type */
        $pattern = [
            "/\bVery large ships can pass through this wormhole\b/"             => 1,
            "/\bLarger ships can pass through this wormhole\b/"                 => 2,
            "/\bUp to medium sized ships can pass through this wormhole\b/"      => 3,
            "/\bOnly the smallest ships can pass through this wormhole\b/"      => 4,
        ];
        foreach ($pattern as $key => $type) {
            if (preg_match($key, $ship_size)) {
                $wormhole_details['ship_size'] = $type;
            }
        }
        // dd($wormhole_details);

        $connectionID = Signature::where('id', $sigID)->value('connection_id');
        if ($connectionID > 0) {
            $sigs = Signature::where('connection_id', $connectionID)->get();
            foreach ($sigs as $sig) {
                $oldLife = $sig->wormhole_info_time_till_death_id;
                $sig->update([
                    'wormhole_info_leads_to_id' => $wormhole_details['leads_to'],
                    'wormhole_info_time_till_death_id' => $wormhole_details['life'],
                    'wormhole_info_mass_id' => $wormhole_details['mass'],
                    'wormhole_info_ship_size_id' => $wormhole_details['ship_size'],
                    'modified_by_id' => Auth::id(),
                    'modified_by_name' => Auth::User()->name,

                ]);

                $sig->wormhole_info_leads_to_id = $wormhole_details['leads_to'];
                $sig->wormhole_info_time_till_death_id = $wormhole_details['life'];
                $sig->wormhole_info_mass_id = $wormhole_details['mass'];
                $sig->wormhole_info_ship_size_id = $wormhole_details['ship_size'];
                $sig->modified_by_id = Auth::id();
                $sig->modified_by_name = Auth::User()->name;

                if ($oldLife != $wormhole_details['life']) {
                    if ($wormhole_details['life'] == 3) {
                        $date = now()->addHours(4);
                        $sig->life_left = $date;
                    }
                }

                $sig->log_helper = 24;
                $sig->save();
            }

            $connections = Signature::where('connection_id', $connectionID)->get();
            foreach ($connections as $connection) {
                $message = Helper::trackingSig($connection->id);

                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'system_id' => $message->system_id,
                ]);
                broadcast(new MappingUpdate($flag));

                $flag = collect([
                    'flag' => 1,
                ]);
                broadcast(new AllConnectionsUpdate($flag));

                StatsHelper::allTheStatsBcastSoloID($connection->created_by_id);
            }

            $valid_connection = Connections::where('id', $connectionID)
                ->whereHas('targetSig', function (Builder $query) {
                    $query->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id');
                })->count();

            if ($valid_connection > 0) {
                $check = Connections::where('id', $connectionID)->first();
                if (!$check->completed_user_id) {
                    $checkUser = Auth::user();
                    $trusted = 0;
                    if ($checkUser->can('connections_trusted')) {
                        $trusted = 1;
                    }
                    Connections::where('id', $connectionID)->update(['completed_user_id' => Auth::id(), 'reserved' => 2, 'trusted' => $trusted]);
                    ConnectionPayment::create(['id' => $connectionID, 'user_id' => Auth::id()]);
                }
                if ($check->jabber_ping == 0) {
                    RouteHelper::jabberCheck($connectionID);
                }
            }
        } else {
            $sig = Signature::where('id', $sigID)->first();
            $oldLife = $sig->wormhole_info_time_till_death_id;
            $sig->update([
                'wormhole_info_leads_to_id' => $wormhole_details['leads_to'],
                'wormhole_info_time_till_death_id' => $wormhole_details['life'],
                'wormhole_info_mass_id' => $wormhole_details['mass'],
                'wormhole_info_ship_size_id' => $wormhole_details['ship_size'],
                'modified_by_id' => Auth::id(),
                'modified_by_name' => Auth::User()->name,

            ]);

            $sig->wormhole_info_leads_to_id = $wormhole_details['leads_to'];
            $sig->wormhole_info_time_till_death_id = $wormhole_details['life'];
            $sig->wormhole_info_mass_id = $wormhole_details['mass'];
            $sig->wormhole_info_ship_size_id = $wormhole_details['ship_size'];
            $sig->modified_by_id = Auth::id();
            $sig->modified_by_name = Auth::User()->name;

            if ($oldLife != $wormhole_details['life']) {
                if ($wormhole_details['life'] == 3) {
                    $date = now()->addHours(4);
                    $sig->life_left = $date;
                }
            }

            $sig->log_helper = 24;
            $sig->save();

            $message = Helper::trackingSig($sigID);

            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $message->system_id,
            ]);
            broadcast(new MappingUpdate($flag));

            $flag = collect([
                'flag' => 1,
            ]);
            broadcast(new AllConnectionsUpdate($flag));

            $userID = Signature::where('id', $sigID)->value('created_by_id');
            StatsHelper::allTheStatsBcastSoloID($userID);

            $flag = collect([
                'flag' => 5,
                'user_id' => Auth::id(),
            ]);

            broadcast(new RouteUpdate($flag));
        }
        holeDone($sig->id);

        // $message = Signature::where('id', $sigID)->with([
        //     'wormhole_type.type',
        //     'solar_system',
        //     'linked_solar_system.systemType',
        //     'wormholeInfoMass',
        //     'wormholeInfoShipSize',
        //     'wormholeInfoTimeTillDeath'
        // ])->first();

        // Broadcast Goes Here

        return response()->json($wormhole_details, 200);
    }
}
