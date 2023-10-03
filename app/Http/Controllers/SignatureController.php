<?php

namespace App\Http\Controllers;

use App\Events\AllConnectionsUpdate;
use App\Events\AllTheStatsUpdate;
use App\Events\MappingUpdate;
use App\Events\WhaleUpdate;
use App\Models\CharTracking;
use App\Models\ConnectionHistory;
use App\Models\ConnectionPayment;
use App\Models\Connections\Connections;
use App\Models\EVE\Characters;
use App\Models\ReserveSig;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use App\Models\SignatureHistory;
use App\Models\SiteSetting;
use App\Models\UserSigReport;
use App\Models\Wormholes\WormholeStatics;
use App\Models\Wormholes\WormholeType;
use App\Models\Zkill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use utils\Helper\Helper;
use utils\RouteHelper\RouteHelper;
use utils\StatsHelper\StatsHelper;

class SignatureController extends Controller
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
        $user = Auth::user();
        $sigStatus = SiteSetting::where('id', 1)->first();
        $run = false;

        if ($sigStatus->settings['show_sig_list'] == 1) {
            $run = true;
        } else {
            if ($user->can('view_sigs')) {
                $run = true;
            }
        }

        if ($run) {
            $data = Helper::sigsAll();

            return ['sigs' => $data];
        }
    }

    public function addUser(Request $request)
    {
        $sig = Signature::where('id', $request->sig_id)->first();
        $type = $sig->signature_group_id;
        $new = new ReserveSig();
        $new->signature_id = $request->sig_id;
        $new->user_id = $request->user_id;

        if ($type == 4) {
            $new->log_helper = 50;
        } else {
            $new->log_helper = 48;
        }
        $new->save();
        Helper::sigsBcastSolo($request->sig_id, 1);
    }

    public function removeUser($id)
    {
        $sigReserve = ReserveSig::where('id', $id)->first();
        $sigID = $sigReserve->signature_id;
        $sig = Signature::where('id', $sigID)->first();
        $type = $sig->signature_group_id;
        activity();
        if ($type == 4) {
            activity()->withoutLogs(function () use ($sigReserve) {
                // ...
                $sigReserve->update(['log_helper' => 51]);
            });
        } else {
            activity()->withoutLogs(function () use ($sigReserve) {
                // ...
                $sigReserve->update(['log_helper' => 49]);
            });
        }
        $sigReserve->delete();
        Helper::sigsBcastSolo($sigID, 1);
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
        $run = false;
        $chars = Characters::where('user_id', Auth::id())->where('tracking', '>=', 1)->get();
        foreach ($chars as $char) {
            $locations = CharTracking::where('character_id', $char->id)->get();
            foreach ($locations as $location) {
                if ($location->current_system_id == $id) {
                    $run = true;
                }
            }
        }

        if (!$run) {
            return null;
        }
        $kills = Zkill::where('solar_system_id', $id)->where('created_at', '>=', Carbon::now()->subDay())->count();
        $date = Helper::allSig($id);
        //@scopeh this just grabs all non-deleted sigs for a given system
        return [
            'sigs' => $date,
            'kills' => $kills,
        ];
    }

    public function restoreSig($id)
    {
        $user_id = Auth::id();
        $user_name = Auth::user()->name;
        $restore = Signature::where('id', $id)->first();
        if ($restore->connection_id) {
            $connection = Connections::where('id', $restore->connection_id)->first();
            $otherSig = Signature::where('id', '!=', $restore->id)->where('connection_id', $connection->id)->first();
            $connection->update(['delete_flag' => 0]);
            $otherSig->update(['leads_to' => $restore->system_id, 'delete' => 0, 'modified_by_id' => $user_id, 'modified_by_name' => $user_name]);
            $restore->update(['leads_to' => $otherSig->system_id, 'delete' => 0, 'modified_by_id' => $user_id, 'modified_by_name' => $user_name]);

            $message = Helper::trackingSig($restore->id);
            $messageSystemID = $message->system_id;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $messageSystemID,
            ]);
            broadcast(new MappingUpdate($flag));

            $message = Helper::trackingSig($otherSig->id);
            $messageSystemID = $message->system_id;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $messageSystemID,
            ]);
            broadcast(new MappingUpdate($flag));

            StatsHelper::allTheStatsBcastSoloID($otherSig->created_by_id);
            $flag = collect([
                'flag' => 1,
            ]);
            broadcast(new AllConnectionsUpdate($flag));
        } else {
            $restore->update(['delete' => 0, 'modified_by_id' => $user_id, 'modified_by_name' => $user_name]);
            $message = Helper::trackingSig($restore->id);
            $messageSystemID = $message->system_id;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $messageSystemID,
            ]);
            broadcast(new MappingUpdate($flag));
            $flag = collect([
                'flag' => 1,
            ]);
            broadcast(new AllConnectionsUpdate($flag));
        }

        StatsHelper::allTheStatsBcastSoloID($restore->created_by_id);
    }

    public function clearWhaleSigs($systemID)
    {
        if (!Auth::user()->can('view_whalers')) {
            return null;
        }
        $delete = false;
        $sigs = Signature::where('system_id', $systemID)->where('created_by_id', 2)->whereNull('modified_by_id')->get();
        foreach ($sigs as $sig) {
            $connectionID = $sig->connection_id;

            $linkedsigs = Signature::where('connection_id', $connectionID)->get();
            foreach ($linkedsigs as $linkedsig) {
                if ($linkedsig->modified_by_id == null) {
                    $delete = true;
                }
            }

            if ($delete) {
                foreach ($linkedsigs as $linkedsig) {
                    activity()->withoutLogs(function () use ($linkedsig) {
                        $linkedsig->log_helper = 31;
                        $linkedsig->save();
                    });
                    $linkedsig->delete();

                    $flag = collect([
                        'id' => $linkedsig->id,
                        'flag' => 2,
                        'system_id' => $linkedsig->system_id,
                    ]);

                    broadcast(new MappingUpdate($flag));

                    $flag = collect([
                        'flag' => 1,
                    ]);
                    broadcast(new AllConnectionsUpdate($flag));
                }

                $connections = Connections::where('id', $connectionID)->get();
                foreach ($connections as $connection) {
                    activity()->withoutLogs(function () use ($connection) {
                        $connection->log_helper = 32;
                        $connection->save();
                    });
                    $connection->delete();
                }

                $message = whaleNumbersSoloSystemID($systemID);
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                ]);
                broadcast(new WhaleUpdate($flag));
            }
        }
    }

    public function addWhaleSig(Request $request)
    {
        if (!Auth::user()->can('view_whalers')) {
            return null;
        }

        $text = $request->drfitInfoPaste ?? null;

        if ($text) {
            $lines = explode("\n", $text);
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
                    // dd($wormhole_details['life'], "===", $type);
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

            if ($wormhole_details['life'] == 1) {
                $addHours = 24;
            }

            if ($wormhole_details['life'] == 2) {
                $addHours = 16;
            }

            if ($wormhole_details['life'] == 3) {
                $addHours = 4;
            }

            $mass = $wormhole_details['mass'];
            $timetilldeath = $wormhole_details['life'];
        } else {
            $timetilldeath = $request->driftLife;
            $mass = $request->driftMass;

            if ($timetilldeath == 1) {
                $addHours = 24;
            }

            if ($timetilldeath == 2) {
                $addHours = 16;
            }

            if ($timetilldeath == 3) {
                $addHours = 4;
            }
        }

        switch ($request->drifterDropDownSelected) {
            case 31000002:
                $wormholeType = WormholeType::whereWormholeType("B735")->pluck('id')->first();
                break;

            case 31000004:
                $wormholeType = WormholeType::whereWormholeType("C414")->pluck('id')->first();
                break;

            case 31000006:
                $wormholeType = WormholeType::whereWormholeType("R259")->pluck('id')->first();
                break;

            case 31000001:
                $wormholeType = WormholeType::whereWormholeType("S877")->pluck('id')->first();
                break;

            case 31000003:
                $wormholeType = WormholeType::whereWormholeType("V928")->pluck('id')->first();
                break;
        }

        $deathTime = now()->addHours($addHours);
        $driftCount = Signature::where('system_id', $request->system_id)->where('name_id', 'DRIFT')->count();
        if ($driftCount > 0) {
            $outsideSigID = 'DRIFT-1';
        } else {
            $num = $driftCount + 1;
            $outsideSigID = 'DRIFT-' . $num;
        }
        // dd($timetilldeath);
        $outside = new Signature();
        $outside->signature_id = $outsideSigID;
        $outside->name = 'DRIFT';
        $outside->system_id = $request->system_id;
        $outside->signature_group_id = 1;
        $outside->name = 'Unstable Wormhole';
        $outside->leads_to = $request->drifterDropDownSelected;
        $outside->life_time = now();
        $outside->life_left = $deathTime;
        $outside->created_by_id = 2;
        $outside->created_by_name = 'Whales';
        $outside->wormhole_info_ship_size_id = $wormhole_details['ship_size'];
        $outside->wormhole_info_leads_to_id = 1;
        $outside->wormhole_info_time_till_death_id = $timetilldeath;
        $outside->wormhole_info_mass_id = $mass;
        $outside->type = $wormholeType;
        $outside->completed_by_name = 'Whales';
        $outside->completed_by_id = 2;
        $outside->log_helper = 33;
        $outside->save();



        $inside = new Signature();
        $inside->system_id = $request->drifterDropDownSelected;
        $inside->signature_group_id = 1;
        $inside->name = 'Unstable Wormhole';
        $inside->leads_to = $request->system_id;
        $inside->life_time = now();
        $inside->life_left = $deathTime;
        $inside->created_by_id = 2;
        $inside->created_by_name = 'Whales';
        $inside->wormhole_info_ship_size_id = $wormhole_details['ship_size'];
        $inside->wormhole_info_leads_to_id = 1;
        $inside->wormhole_info_time_till_death_id = $timetilldeath;
        $inside->wormhole_info_mass_id = $mass;
        $inside->type = 420;
        $inside->log_helper = 33;
        $inside->save();

        $connection = new Connections();
        $connection->source_sig_id = $outside->id;
        $connection->target_sig_id = $inside->id;
        $connection->source_system_id = $request->system_id;
        $connection->target_system_id = $request->drifterDropDownSelected;
        $connection->type = 2;
        $connection->log_helper = 34;
        $connection->save();

        $inside->update(['connection_id' => $connection->id]);
        $outside->update(['connection_id' => $connection->id]);

        $message = whaleNumbersSoloSystemID($request->system_id);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
        ]);
        broadcast(new WhaleUpdate($flag));

        $message = Helper::trackingSig($inside->id);

        // dd($message);
        $system_id = $message->system_id;
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $message = Helper::trackingSig($outside->id);

        // dd($message);
        $system_id = $message->system_id;
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));

        drifterAdded($outside->id);
    }

    public function addDrift(Request $request, $id)
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

        $new = new Signature();
        $new->signature_id = $request->signature_id;
        $new->signal_strength = 100;
        $new->name_id = $request->name_id;
        $new->system_id = $request->system_id;
        $new->signature_group_id = $request->signature_group_id;
        $new->name = 'Drifter Wormhole';
        $new->life_time = $request->life_time;
        $new->created_by_name = Auth::user()->name;
        $new->modified_by_name = $request->created_by_name;
        $new->modified_by_id = $request->modified_by_id;
        $new->created_by_id = Auth::id();
        $new->log_helper = 1;
        $new->save();

        $sigID = $new->id;

        $message = Helper::trackingSig($sigID);

        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));

        StatsHelper::allTheStatsBcastSoloID(Auth::id());

        drifterAdded($new->id);
    }

    public function addSigID(Request $request)
    {
        $run = null;
        $checkSig = Signature::where('id', $request->id)->first();
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->current_system_id == $checkSig->system_id) {
                $run = true;
            }
        }

        if (!$run) {
            return null;
        }

        $flag = collect([
            'id' => $request->old_id,
            'flag' => 2,
            'system_id' => $request->current_system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));
        $oldsig = Signature::where('id', $request->old_id)->first();
        $sig = Signature::where('id', $request->id)->first();

        if (!$sig->type) {
            $sig->type = $oldsig->type;
        }

        if (!$sig->wormhole_info_ship_size_id) {
            $sig->wormhole_info_ship_size_id = $oldsig->wormhole_info_ship_size_id;
            $sig->wormhole_info_leads_to_id = $oldsig->wormhole_info_leads_to_id;
            $sig->wormhole_info_mass_id = $oldsig->wormhole_info_mass_id;
            $sig->wormhole_info_time_till_death_id = $oldsig->wormhole_info_time_till_death_id;

            Signature::where('id', '!=', $sig->id)->where('connection_id', $sig->connection_id)->update([
                'wormhole_info_ship_size_id' => $oldsig->wormhole_info_ship_size_id,
                'wormhole_info_leads_to_id' => $oldsig->wormhole_info_leads_to_id,
                'wormhole_info_mass_id' => $oldsig->wormhole_info_mass_id,
                'wormhole_info_time_till_death_id' => $oldsig->wormhole_info_time_till_death_id,
            ]);
        }

        $sig->signature_id = $request->signature_id;
        $sig->modified_by_id = $request->modified_by_id;
        $sig->modified_by_name = $request->modified_by_name;
        $sig->signal_strength = 100.00;
        $sig->leads_to = $request->last_system_id;
        $sig->completed_by_name = $oldsig->completed_by_name ?? null;
        $sig->completed_by_id = $oldsig->completed_by_id ?? null;
        $sig->name_id = $oldsig->name_id;
        $sig->name = "Unstable Wormhole";
        $sig->log_helper = 5;
        $sig->save();

        WormholeStatics::whereSystemId($sig->system_id)->whereWormholeTypeId($sig->type)->update(['signature_id' => $sig->id]);

        $message = Helper::trackingSig($request->id);
        $oldsigUserID = $oldsig->created_by_id;
        SavedRoute::whereLink($oldsig->route_link)->delete();
        SavedRoute::whereLink($oldsig->route_link_p)->delete();
        removeStaticDone($oldsig->id);
        $oldsig->delete();
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

        $last_sig_id = Connections::where('type', 2)->where('target_sig_id', $request->id)->value('source_sig_id');

        $message = Helper::trackingSig($last_sig_id);

        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $message->system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        StatsHelper::allTheStatsBcastSoloID(Auth::id());
        StatsHelper::allTheStatsBcastSoloID($oldsigUserID);

        $valid_connection = Connections::where('id', $sig->connection_id)
            ->whereHas('targetSig', function (Builder $query) {
                $query->where('delete', 0)
                    ->whereNotNull('signature_id')
                    ->whereNotNull('wormhole_info_ship_size_id')
                    ->whereNotNull('wormhole_info_leads_to_id')
                    ->whereNotNull('wormhole_info_mass_id')
                    ->whereNotNull('wormhole_info_time_till_death_id');
            })->count();

        if ($valid_connection > 0) {
            $check = Connections::where('id', $sig->connection_id)->first();
            if (!$check->completed_user_id) {
                $checkUser = Auth::user();
                $trusted = 0;
                if ($checkUser->can('connections_trusted')) {
                    $trusted = 1;
                }
                Connections::where('id', $sig->connection_id)->update(['completed_user_id' => Auth::id(), 'reserved' => 2, 'trusted' => $trusted]);
                ConnectionPayment::create(['id' => $sig->connection_id, 'user_id' => Auth::id()]);
            }
            if ($check->jabber_ping == 0) {
                RouteHelper::jabberCheck($sig->connection_id);
            }
        }
        holeDone($request->id);
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
        $run = false;
        $sig = Signature::where('id', $id)->first();
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->current_system_id == $sig->system_id) {
                $run = true;
            }
        }
        if (!$run) {
            return null;
        }

        $linkedID = null;
        if ($request->type) {
            if ($request->type != 420) {
                $add = WormholeType::where('id', $request->type)->value('life');
                $startDate = Signature::where('id', $id)->value('life_time');
                $date = Carbon::parse($startDate)->addHours($add);
                $connectionID = Signature::where('id', $id)->value('connection_id');
                if ($connectionID > 0) {
                    $linkedID = Signature::where('connection_id', $connectionID)->where('id', '!=', $id)->value('id');
                    Signature::where('connection_id', $connectionID)->update(['life_left' => $date]);

                    $message = Helper::trackingSig($linkedID);
                    $system_id = $message->system_id;
                    $flag = collect([
                        'flag' => 1,
                        'message' => $message,
                        'system_id' => $system_id,
                    ]);
                    broadcast(new MappingUpdate($flag));

                    $flag = collect([
                        'flag' => 1,
                    ]);
                    broadcast(new AllConnectionsUpdate($flag));

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
                    $sig->life_left = $date;
                }
            }
        }

        $sig->modified_by_id = $request->modified_by_id;
        $sig->modified_by_name = $request->modified_by_name;
        $sig->type = $request->type;
        $sig->log_helper = 20;
        $sig->save();
        $message = Helper::trackingSig($id);

        // dd($message);
        $system_id = $message->system_id;
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));

        $userID = Signature::where('id', $id)->value('created_by_id');
        StatsHelper::allTheStatsBcastSoloID($userID);

        holeDone($id);
    }

    public function sigReport($id)
    {
        $run = false;
        $sig = Signature::where('id', $id)->first();
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            if ($char->current_system_id == $sig->system_id || $char->last_system_id == $sig->system_id) {
                $run = true;
            }
        }
        $spam = Activity::where('causer_id', Auth::id())->where('subject_id', $sig->id)->where('description', 'Reported Sig Gone')->count();
        if ($spam > 0) {
            $run = false;
        }
        if (!$run) {
            return null;
        }

        $count = $sig->report_count;
        $newCount = $count + 1;

        $sig->report_count = $newCount;
        $sig->log_helper = 35;
        $sig->save();

        UserSigReport::create([
            'user_id' => Auth::id(),
            'signature_id' => $id,
        ]);

        if ($newCount == 10) {
            $this->sigDone($sig->id);
        }
    }

    public function getBrokenConnections()
    {
        $user = Auth::user();
        if ($user->can('view_broken_connections')) {
            $sigs = allBroken();
            return ['broken' => $sigs];
        } else {
            return null;
        }
    }

    public function sigDone($id)
    {
        if (!Auth::user()->can('delete_sigs')) {
            $reportCount = Signature::where('id', $id)->value('report_count');
            if ($reportCount < 10) {
                return null;
            }
        } else {
            $reportCount = Signature::where('id', $id)->value('report_count');
        }

        $causerTypeID = 1;
        $causerID = Auth::id();
        if ($reportCount >= 10) {
            $causerTypeID = 2;
            $causerID = 0;
        }

        $connectionID = Signature::where('id', $id)->value('connection_id');
        $connection = Connections::where('id', $connectionID)->first();
        $oldUserIdsGet = Signature::where('connection_id', $connectionID)->get();
        $oldUserIDs = $oldUserIdsGet->unique('created_by_id')->pluck('created_by_id');
        if ($connectionID) {
            $sigs = Signature::where('connection_id', $connectionID)->get();
        } else {
            $sigs = Signature::where('id', $id)->get();
        }

        foreach ($sigs as $oldSig) {
            $old_id = $oldSig->id;
            $old_system = $oldSig->system_id;
            SignatureHistory::updateOrCreate([
                'id' => $oldSig->id ?? null
            ], [
                'name_id' => $oldSig->name_id ?? null,
                'signature_id' => $oldSig->signature_id ?? null,
                'system_id' => $oldSig->system_id ?? null,
                'type' => $oldSig->type ?? null,
                'signature_group_id' => $oldSig->signature_group_id ?? null,
                'name' => $oldSig->name ?? null,
                'leads_to' => $oldSig->leads_to ?? null,
                'connection_id' => $oldSig->connection_id ?? null,
                'signal_strength' => $oldSig->signal_strength ?? null,
                'bookmark_syntax' => $oldSig->bookmark_syntax ?? null,
                'life_time' => $oldSig->life_time ?? null,
                'life_left' => $oldSig->life_left ?? null,
                'delete' => $oldSig->delete ?? null,
                'created_by_id' => $oldSig->created_by_id ?? null,
                'created_by_name' => $oldSig->created_by_name ?? null,
                'modified_by_id' => $oldSig->modified_by_id ?? null,
                'modified_by_name' => $oldSig->modified_by_name ?? null,
                'wormhole_info_ship_size_id' => $oldSig->wormhole_info_ship_size_id ?? null,
                'wormhole_info_leads_to_id' => $oldSig->wormhole_info_leads_to_id ?? null,
                'wormhole_info_mass_id' => $oldSig->wormhole_info_mass_id ?? null,
                'wormhole_info_time_till_death_id' => $oldSig->wormhole_info_time_till_death_id ?? null,
                'created_at' => $oldSig->created_at ?? null,
                'updated_at' => $oldSig->updated_at ?? null,
                'completed_by_id' => $oldSig->completed_by_id ?? null,
                'completed_by_name' => $oldSig->completed_by_name ?? null,
            ]);

            Activity::where('subject_id', $oldSig->id)->where('subject_type', 'App\Models\Scanning\Signature')->update(['subject_type' => 'App\Models\SignatureHistory']);

            $oldConnection = Connections::where('id', $connectionID)->first();
            if ($oldConnection) {
                $new = ConnectionHistory::updateOrCreate([
                    'id' => $oldConnection->id ?? null
                ], [
                    'source_sig_id' => $oldConnection->source_sig_id ?? null,
                    'target_sig_id' => $oldConnection->target_sig_id ?? null,
                    'source_system_id' => $oldConnection->source_system_id ?? null,
                    'target_system_id' => $oldConnection->target_system_id ?? null,
                    'type' => $oldConnection->type ?? null,
                    'delete_flag' => $oldConnection->delete_flag ?? null,
                    'created_at' => $oldConnection->created_at ?? null,
                    'updated_at' => $oldConnection->updated_at ?? null,
                    'completed_user_id' => $oldConnection->completed_user_id ?? null,
                ]);
                $new->update(['id' => $oldConnection->id]);

                Activity::where('subject_id', $oldConnection->id)->where('subject_type', 'App\Models\Connections\Connections')->update(['subject_type' => 'App\Models\ConnectionHistory']);
                $oldConnection->delete();
            }
            SavedRoute::where('link', $oldSig->route_link)->delete();
            SavedRoute::where('link', $oldSig->route_link_p)->delete();

            Signature::where('id', $old_id)->update(['log_helper' => 37]);

            $SigDelete = Signature::where('id', $old_id)->first();
            removeStaticDone($SigDelete->id);
            $SigDelete->delete();

            Helper::sigsBcastSolo($old_id, 2);
            $flag = null;
            $flag = collect([
                'id' => $old_id,
                'flag' => 2,
                'system_id' => $old_system,
            ]);
            broadcast(new MappingUpdate($flag));

            $flag = collect([
                'flag' => 1,
            ]);
            broadcast(new AllConnectionsUpdate($flag));
        }

        foreach ($oldUserIDs as $oldUserID) {
            StatsHelper::allTheStatsBcastSoloID($oldUserID);
        }
    }

    public function softDelete(Request $request, $id)
    {
        $runSigID = Signature::where('id', $id)->first();
        if (Auth::id() != $runSigID->created_by_id) {
            return null;
        }

        $connectionID = Signature::where('id', $id)->value('connection_id');
        $connection = Connections::where('id', $connectionID)->first();

        if ($connection) {
            Signature::where('connection_id', $connection->id)->update(['leads_to' => null, 'modified_by_id' => Auth::id(), 'modified_by_name' => Auth::user()->name]);

            $sigID = Signature::where('id', $connection->source_sig_id)->value('id');
            $userID1 = Signature::where('id', $connection->source_sig_id)->value('created_by_id');
            $message = Helper::trackingSig($sigID);
            $messageSystemID = $message->system_id;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $messageSystemID,
            ]);
            broadcast(new MappingUpdate($flag));

            $sigID = Signature::where('id', $connection->target_sig_id)->value('id');
            $userID2 = Signature::where('id', $connection->target_sig_id)->value('created_by_id');
            $message = Helper::trackingSig($sigID);
            $messageSystemID = $message->system_id;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'system_id' => $messageSystemID,
            ]);
            broadcast(new MappingUpdate($flag));
            $connection->update(['delete_flag' => 1]);

            $flag = collect([
                'flag' => 1,
            ]);
            broadcast(new AllConnectionsUpdate($flag));

            StatsHelper::allTheStatsBcastSoloID($userID1);
            StatsHelper::allTheStatsBcastSoloID($userID2);
        }

        //@scopeh this is to update a sig to deleted then boradcasts it to everyone in that system
        Signature::where('id', $id)->update($request->all());
        $sigSoftDelete = Signature::where('id', $id)->first();
        $sigSoftDelete->delete = $request->delete;
        $sigSoftDelete->log_helper = 18;
        $sigSoftDelete->save();

        $userID3 = Signature::where('id', $id)->value('created_by_id');

        $message = Helper::trackingSig($id);
        $system_id = $message->system_id;
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));

        $message = Helper::allTheStatsUsersByID($userID3);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
        ]);
        broadcast(new AllTheStatsUpdate($flag));

        Helper::sigsBcastSolo($id, 2);
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
