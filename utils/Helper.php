<?php

namespace utils\Helper;

use App\Events\SigspUpdate;
use App\Events\SigsUpdate;
use App\Models\JoveSystems;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use App\Models\User;
use App\Models\UserSigReport;
use App\Models\Wormholes\WormholeStatics;
use App\Models\Wormholes\WormholeType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use utils\StatsHelper\StatsHelper;

class Helper
{
    use HasRoles;
    use HasPermissions;

    public static function jabberPing($connection_id)
    {
        return 'Laravel fefefFramework';
    }

    public static function userIdFromName($name)
    {
        $userIds = collect();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'webway eve@lol.com',
        ])
            ->withBody(json_encode([$name]), 'application/json')
            ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

        $res = $response->collect($key = null);
        foreach ($res as $key => $re) {
            if ($key == 'characters') {
                $userIds->push($re[0]['id']);
            }
        }

        return $userIds->first();
    }

    public static function displayName()
    {
        return 'Laravel fefefFramework';
    }

    public static function systemChars($system_id)
    {
        return  SolarSystem::where('system_id', $system_id)->with(['characters' => function ($t) {
            $t->where('tracking', '>=', 1)
                ->select(['id', 'name', 'current_system_id']);
        }])
            ->first();
    }

    public static function clearRemember()
    {
        $now = now()->modify('-3 days');
        User::where('updated_at', '<', $now)->update(['remember_token' => null]);
    }

    public static function trackingSig($id)
    {
        return Signature::where('id', $id)->with([
            'group',
            'wormhole_type.type',
            'solar_system.constellation',
            'solar_system.region',
            'linked_solar_system.systemType',
            'linked_solar_system.constellation',
            'linked_solar_system.region',
            'wormholeInfoMass',
            'wormholeInfoShipSize',
            'wormholeInfoTimeTillDeath',
            'notes',
            'nextSystemSigs:id,system_id,signature_group_id,completed_by_id'
        ])->first();
    }

    public static function allSig($id)
    {
        $userID = FacadesAuth::id();
        $reported = UserSigReport::where('user_id', $userID)->pluck('id');

        return Signature::where('system_id', $id)
            ->with([
                'group',
                'wormhole_type.type',
                'solar_system.constellation',
                'solar_system.region',
                'linked_solar_system.systemType',
                'linked_solar_system.constellation',
                'linked_solar_system.region',
                'wormholeInfoMass',
                'wormholeInfoShipSize',
                'wormholeInfoTimeTillDeath',
                'notes.user:id,name',
                'nextSystemSigs:id,system_id,signature_group_id,completed_by_id'

            ])
            ->where('delete', 0)
            ->whereNotIn('id', $reported)
            ->orderBy('signature_id', 'ASC')
            ->get();
    }

    public static function allTheStatsUsersByID($id)
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $current = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMade as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('delete_flag', 0)
                        ->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month);
                },
                'connectionsCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->where('delete_flag', 0);
                },

                'connectionsMade as connectionsPart' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAll as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAll as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
            ])->first();

        $old = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMadeHistory as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('delete_flag', 0)
                        ->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month);
                },
                'connectionHistoriesCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMadeHistory as connectionsPart' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAllHistory as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAllHistory as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
            ])->first();

        $connectionsDone = $current->connectionsDone + $old->connectionsDone;
        $connectionsAll = $current->connectionsAll + $old->connectionsAll;
        $sigsAll = $current->sigsAll + $old->sigsAll;
        $connectionsPart = $current->connectionsPart + $old->connectionsPart;
        $sigsAllData = $current->sigsAllData + $old->sigsAllData;
        $sigsAllCombat = $current->sigsAllCombat + $old->sigsAllCombat;
        $sigsAllOre = $current->sigsAllOre + $old->sigsAllOre;
        $sigsAllGas = $current->sigsAllGas + $old->sigsAllGas;
        $sigsAllUnknown = $current->sigsAllUnknown + $old->sigsAllUnknown;
        $sigsAllRelic = $current->sigsAllRelic + $old->sigsAllRelic;
        $sigsDoneAll = $current->sigsDoneAll + $old->sigsDoneAll;
        $sigsAllWormholes = $current->sigsAllWormholes + $old->sigsAllWormholes;
        $sigsDoneData = $current->sigsDoneData + $old->sigsDoneData;
        $sigsDoneCombat = $current->sigsDoneCombat + $old->sigsDoneCombat;
        $sigsDoneOre = $current->sigsDoneOre + $old->sigsDoneOre;
        $sigsDoneGas = $current->sigsDoneGas + $old->sigsDoneGas;
        $sigsDoneWormholes = $current->sigsDoneWormholes + $old->sigsDoneWormholes;
        $sigsDoneRelic = $current->sigsDoneRelic + $old->sigsDoneRelic;
        $sigsPartCombat = $current->sigsPartCombat + $old->sigsPartCombat;
        $sigsPartAll = $current->sigsPartAll + $old->sigsPartAll;
        $sigsPartGas = $current->sigsPartGas + $old->sigsPartGas;
        $sigsPartData = $current->sigsPartData + $old->sigsPartData;
        $sigsPartRelic = $current->sigsPartRelic + $old->sigsPartRelic;
        $sigsPartOre = $current->sigsPartOre + $old->sigsPartOre;
        $sigsPartWormholes = $current->sigsPartWormholes + $old->sigsPartWormholes;

        $current->connectionsDone = $connectionsDone;
        $current->connectionsAll = $connectionsAll;
        $current->sigsAll = $sigsAll;
        $current->connectionsPart = $connectionsPart;
        $current->sigsAllData = $sigsAllData;
        $current->sigsAllCombat = $sigsAllCombat;
        $current->sigsAllOre = $sigsAllOre;
        $current->sigsAllGas = $sigsAllGas;
        $current->sigsAllUnknown = $sigsAllUnknown;
        $current->sigsAllRelic = $sigsAllRelic;
        $current->sigsDoneAll = $sigsDoneAll;
        $current->sigsAllWormholes = $sigsAllWormholes;
        $current->sigsDoneData = $sigsDoneData;
        $current->sigsDoneCombat = $sigsDoneCombat;
        $current->sigsDoneOre = $sigsDoneOre;
        $current->sigsDoneGas = $sigsDoneGas;
        $current->sigsDoneWormholes = $sigsDoneWormholes;
        $current->sigsDoneRelic = $sigsDoneRelic;
        $current->sigsPartCombat = $sigsPartCombat;
        $current->sigsPartAll = $sigsPartAll;
        $current->sigsPartGas = $sigsPartGas;
        $current->sigsPartData = $sigsPartData;
        $current->sigsPartRelic = $sigsPartRelic;
        $current->sigsPartOre = $sigsPartOre;
        $current->sigsPartWormholes = $sigsPartWormholes;

        return $current;
    }

    public static function allTheStatsUsersLastMonth()
    {
        $now = Carbon::now()->subMonth();
        $month = $now->month;
        $year = $now->year;
        // dd($year);
        $current = User::where('id', '>', 5)
            ->select('id', 'name')
            ->withCount([
                'connectionsMade as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->where('delete_flag', 0)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month);
                },
                'connectionsCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMade as connectionsPart' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAll as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAll as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
            ])->orderBy('id', 'desc')->get();

        $old = User::where('id', '>', 5)
            ->select('id', 'name')
            ->withCount([
                'connectionsMadeHistory as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('delete_flag', 0)
                        ->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month);
                },
                'connectionHistoriesCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMadeHistory as connectionsPart' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAllHistory as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAllHistory as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
            ])->orderBy('id', 'desc')->get();

        $count = $current->count(); //48
        $i = 0;
        do {
            $connectionsDone = $current[$i]->connectionsDone + $old[$i]->connectionsDone;
            $connectionsAll = $current[$i]->connectionsAll + $old[$i]->connectionsAll;
            $sigsAll = $current[$i]->sigsAll + $old[$i]->sigsAll;
            $connectionsPart = $current[$i]->connectionsPart + $old[$i]->connectionsPart;
            $sigsAllData = $current[$i]->sigsAllData + $old[$i]->sigsAllData;
            $sigsAllCombat = $current[$i]->sigsAllCombat + $old[$i]->sigsAllCombat;
            $sigsAllOre = $current[$i]->sigsAllOre + $old[$i]->sigsAllOre;
            $sigsAllGas = $current[$i]->sigsAllGas + $old[$i]->sigsAllGas;
            $sigsAllUnknown = $current[$i]->sigsAllUnknown + $old[$i]->sigsAllUnknown;
            $sigsAllRelic = $current[$i]->sigsAllRelic + $old[$i]->sigsAllRelic;
            $sigsDoneAll = $current[$i]->sigsDoneAll + $old[$i]->sigsDoneAll;
            $sigsAllWormholes = $current[$i]->sigsAllWormholes + $old[$i]->sigsAllWormholes;
            $sigsDoneData = $current[$i]->sigsDoneData + $old[$i]->sigsDoneData;
            $sigsDoneCombat = $current[$i]->sigsDoneCombat + $old[$i]->sigsDoneCombat;
            $sigsDoneOre = $current[$i]->sigsDoneOre + $old[$i]->sigsDoneOre;
            $sigsDoneGas = $current[$i]->sigsDoneGas + $old[$i]->sigsDoneGas;
            $sigsDoneWormholes = $current[$i]->sigsDoneWormholes + $old[$i]->sigsDoneWormholes;
            $sigsDoneRelic = $current[$i]->sigsDoneRelic + $old[$i]->sigsDoneRelic;
            $sigsPartCombat = $current[$i]->sigsPartCombat + $old[$i]->sigsPartCombat;
            $sigsPartAll = $current[$i]->sigsPartAll + $old[$i]->sigsPartAll;
            $sigsPartGas = $current[$i]->sigsPartGas + $old[$i]->sigsPartGas;
            $sigsPartData = $current[$i]->sigsPartData + $old[$i]->sigsPartData;
            $sigsPartRelic = $current[$i]->sigsPartRelic + $old[$i]->sigsPartRelic;
            $sigsPartOre = $current[$i]->sigsPartOre + $old[$i]->sigsPartOre;
            $sigsPartWormholes = $current[$i]->sigsPartWormholes + $old[$i]->sigsPartWormholes;

            $current[$i]->connectionsDone = $connectionsDone;
            $current[$i]->connectionsAll = $connectionsAll;
            $current[$i]->sigsAll = $sigsAll;
            $current[$i]->connectionsPart = $connectionsPart;
            $current[$i]->sigsAllData = $sigsAllData;
            $current[$i]->sigsAllCombat = $sigsAllCombat;
            $current[$i]->sigsAllOre = $sigsAllOre;
            $current[$i]->sigsAllGas = $sigsAllGas;
            $current[$i]->sigsAllUnknown = $sigsAllUnknown;
            $current[$i]->sigsAllRelic = $sigsAllRelic;
            $current[$i]->sigsDoneAll = $sigsDoneAll;
            $current[$i]->sigsAllWormholes = $sigsAllWormholes;
            $current[$i]->sigsDoneData = $sigsDoneData;
            $current[$i]->sigsDoneCombat = $sigsDoneCombat;
            $current[$i]->sigsDoneOre = $sigsDoneOre;
            $current[$i]->sigsDoneGas = $sigsDoneGas;
            $current[$i]->sigsDoneWormholes = $sigsDoneWormholes;
            $current[$i]->sigsDoneRelic = $sigsDoneRelic;
            $current[$i]->sigsPartCombat = $sigsPartCombat;
            $current[$i]->sigsPartAll = $sigsPartAll;
            $current[$i]->sigsPartGas = $sigsPartGas;
            $current[$i]->sigsPartData = $sigsPartData;
            $current[$i]->sigsPartRelic = $sigsPartRelic;
            $current[$i]->sigsPartOre = $sigsPartOre;
            $current[$i]->sigsPartWormholes = $sigsPartWormholes;

            $i++;
        } while ($i < $count);

        return $current;
    }

    public static function allTheStatsUsersLastMonthByID($id)
    {
        $now = Carbon::now()->subMonth();
        $month = $now->month;
        $year = $now->year;

        $current = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMade as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->where('delete_flag', 0)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month);
                },
                'connectionsCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMade as connectionsPart' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAll as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAll as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
            ])->orderBy('id', 'desc')->first();

        $old = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMadeHistory as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('delete_flag', 0)
                        ->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month);
                },
                'connectionHistoriesCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMadeHistory as connectionsPart' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAllHistory as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAllHistory as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
            ])->orderBy('id', 'desc')->first();

        $connectionsDone = $current->connectionsDone + $old->connectionsDone;
        $connectionsAll = $current->connectionsAll + $old->connectionsAll;
        $sigsAll = $current->sigsAll + $old->sigsAll;
        $connectionsPart = $current->connectionsPart + $old->connectionsPart;
        $sigsAllData = $current->sigsAllData + $old->sigsAllData;
        $sigsAllCombat = $current->sigsAllCombat + $old->sigsAllCombat;
        $sigsAllOre = $current->sigsAllOre + $old->sigsAllOre;
        $sigsAllGas = $current->sigsAllGas + $old->sigsAllGas;
        $sigsAllUnknown = $current->sigsAllUnknown + $old->sigsAllUnknown;
        $sigsAllRelic = $current->sigsAllRelic + $old->sigsAllRelic;
        $sigsDoneAll = $current->sigsDoneAll + $old->sigsDoneAll;
        $sigsAllWormholes = $current->sigsAllWormholes + $old->sigsAllWormholes;
        $sigsDoneData = $current->sigsDoneData + $old->sigsDoneData;
        $sigsDoneCombat = $current->sigsDoneCombat + $old->sigsDoneCombat;
        $sigsDoneOre = $current->sigsDoneOre + $old->sigsDoneOre;
        $sigsDoneGas = $current->sigsDoneGas + $old->sigsDoneGas;
        $sigsDoneWormholes = $current->sigsDoneWormholes + $old->sigsDoneWormholes;
        $sigsDoneRelic = $current->sigsDoneRelic + $old->sigsDoneRelic;
        $sigsPartCombat = $current->sigsPartCombat + $old->sigsPartCombat;
        $sigsPartAll = $current->sigsPartAll + $old->sigsPartAll;
        $sigsPartGas = $current->sigsPartGas + $old->sigsPartGas;
        $sigsPartData = $current->sigsPartData + $old->sigsPartData;
        $sigsPartRelic = $current->sigsPartRelic + $old->sigsPartRelic;
        $sigsPartOre = $current->sigsPartOre + $old->sigsPartOre;
        $sigsPartWormholes = $current->sigsPartWormholes + $old->sigsPartWormholes;

        $current->connectionsDone = $connectionsDone;
        $current->connectionsAll = $connectionsAll;
        $current->sigsAll = $sigsAll;
        $current->connectionsPart = $connectionsPart;
        $current->sigsAllData = $sigsAllData;
        $current->sigsAllCombat = $sigsAllCombat;
        $current->sigsAllOre = $sigsAllOre;
        $current->sigsAllGas = $sigsAllGas;
        $current->sigsAllUnknown = $sigsAllUnknown;
        $current->sigsAllRelic = $sigsAllRelic;
        $current->sigsDoneAll = $sigsDoneAll;
        $current->sigsAllWormholes = $sigsAllWormholes;
        $current->sigsDoneData = $sigsDoneData;
        $current->sigsDoneCombat = $sigsDoneCombat;
        $current->sigsDoneOre = $sigsDoneOre;
        $current->sigsDoneGas = $sigsDoneGas;
        $current->sigsDoneWormholes = $sigsDoneWormholes;
        $current->sigsDoneRelic = $sigsDoneRelic;
        $current->sigsPartCombat = $sigsPartCombat;
        $current->sigsPartAll = $sigsPartAll;
        $current->sigsPartGas = $sigsPartGas;
        $current->sigsPartData = $sigsPartData;
        $current->sigsPartRelic = $sigsPartRelic;
        $current->sigsPartOre = $sigsPartOre;
        $current->sigsPartWormholes = $sigsPartWormholes;

        return $current;
    }

    public static function allTheStatsUsersUserCostomMonthYear($id, $month, $year)
    {
        $current = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMade as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->where('delete_flag', 0)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month);
                },
                'connectionsCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMade as connectionsPart' => function ($t) use ($year, $month) {
                    $t->where('connections.type', 2)
                        ->whereYear('connections.created_at', $year)
                        ->whereMonth('connections.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAll as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAll as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAllCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
                'sigsAll as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signatures.created_at', $year)
                        ->whereMonth('signatures.created_at', $month);
                },
            ])->orderBy('id', 'desc')->first();

        $old = User::where('id', $id)
            ->select('id', 'name')
            ->withCount([
                'connectionsMadeHistory as connectionsAll' => function ($t) use ($year, $month) {
                    $t->where('delete_flag', 0)
                        ->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month);
                },
                'connectionHistoriesCompleted as connectionsDone' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->where('delete_flag', 0);
                },
                'connectionsMadeHistory as connectionsPart' => function ($t) use ($year, $month) {
                    $t->whereYear('connection_histories.created_at', $year)
                        ->whereMonth('connection_histories.created_at', $month)
                        ->whereHas('targetSig', function (Builder $query) {
                            $query->where('delete', 0)
                                ->where(function ($q) {
                                    $q->whereNull('signature_id')
                                        ->orWhereNull('wormhole_info_ship_size_id')
                                        ->orWhereNull('wormhole_info_leads_to_id')
                                        ->orWhereNull('wormhole_info_mass_id')
                                        ->orWhereNull('wormhole_info_time_till_death_id');
                                });
                        });
                },

                'sigsAllHistory as sigsAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneAll' => function ($t) use ($year, $month) {
                    $t->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartAll' => function ($t) use ($year, $month) {
                    $t->where('signal_strength', '!=', 100.00)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id')
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartWormholes' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 1)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where(function ($q) {
                            $q->whereNull('signature_id')
                                ->orwhere('signal_strength', '!=', 100.00)
                                ->orWhereNull('wormhole_info_ship_size_id')
                                ->orWhereNull('wormhole_info_leads_to_id')
                                ->orWhereNull('wormhole_info_mass_id')
                                ->orWhereNull('wormhole_info_time_till_death_id');
                        });
                },
                'sigsAllHistory as sigsAllRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartRelic' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 2)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartData' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 3)->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartGas' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 4)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartCombat' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 5)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistoryCompleted as sigsDoneOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsPartOre' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 6)
                        ->where('signal_strength', '!=', 100.00)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
                'sigsAllHistory as sigsAllUnknown' => function ($t) use ($year, $month) {
                    $t->where('signature_group_id', 7)
                        ->where('delete', 0)
                        ->whereYear('signature_histories.created_at', $year)
                        ->whereMonth('signature_histories.created_at', $month);
                },
            ])->first();

        $connectionsDone = $current->connectionsDone + $old->connectionsDone;
        $connectionsAll = $current->connectionsAll + $old->connectionsAll;
        $sigsAll = $current->sigsAll + $old->sigsAll;
        $connectionsPart = $current->connectionsPart + $old->connectionsPart;
        $sigsAllData = $current->sigsAllData + $old->sigsAllData;
        $sigsAllCombat = $current->sigsAllCombat + $old->sigsAllCombat;
        $sigsAllOre = $current->sigsAllOre + $old->sigsAllOre;
        $sigsAllGas = $current->sigsAllGas + $old->sigsAllGas;
        $sigsAllUnknown = $current->sigsAllUnknown + $old->sigsAllUnknown;
        $sigsAllRelic = $current->sigsAllRelic + $old->sigsAllRelic;
        $sigsDoneAll = $current->sigsDoneAll + $old->sigsDoneAll;
        $sigsAllWormholes = $current->sigsAllWormholes + $old->sigsAllWormholes;
        $sigsDoneData = $current->sigsDoneData + $old->sigsDoneData;
        $sigsDoneCombat = $current->sigsDoneCombat + $old->sigsDoneCombat;
        $sigsDoneOre = $current->sigsDoneOre + $old->sigsDoneOre;
        $sigsDoneGas = $current->sigsDoneGas + $old->sigsDoneGas;
        $sigsDoneWormholes = $current->sigsDoneWormholes + $old->sigsDoneWormholes;
        $sigsDoneRelic = $current->sigsDoneRelic + $old->sigsDoneRelic;
        $sigsPartCombat = $current->sigsPartCombat + $old->sigsPartCombat;
        $sigsPartAll = $current->sigsPartAll + $old->sigsPartAll;
        $sigsPartGas = $current->sigsPartGas + $old->sigsPartGas;
        $sigsPartData = $current->sigsPartData + $old->sigsPartData;
        $sigsPartRelic = $current->sigsPartRelic + $old->sigsPartRelic;
        $sigsPartOre = $current->sigsPartOre + $old->sigsPartOre;
        $sigsPartWormholes = $current->sigsPartWormholes + $old->sigsPartWormholes;

        $current->connectionsDone = $connectionsDone;
        $current->connectionsAll = $connectionsAll;
        $current->sigsAll = $sigsAll;
        $current->connectionsPart = $connectionsPart;
        $current->sigsAllData = $sigsAllData;
        $current->sigsAllCombat = $sigsAllCombat;
        $current->sigsAllOre = $sigsAllOre;
        $current->sigsAllGas = $sigsAllGas;
        $current->sigsAllUnknown = $sigsAllUnknown;
        $current->sigsAllRelic = $sigsAllRelic;
        $current->sigsDoneAll = $sigsDoneAll;
        $current->sigsAllWormholes = $sigsAllWormholes;
        $current->sigsDoneData = $sigsDoneData;
        $current->sigsDoneCombat = $sigsDoneCombat;
        $current->sigsDoneOre = $sigsDoneOre;
        $current->sigsDoneGas = $sigsDoneGas;
        $current->sigsDoneWormholes = $sigsDoneWormholes;
        $current->sigsDoneRelic = $sigsDoneRelic;
        $current->sigsPartCombat = $sigsPartCombat;
        $current->sigsPartAll = $sigsPartAll;
        $current->sigsPartGas = $sigsPartGas;
        $current->sigsPartData = $sigsPartData;
        $current->sigsPartRelic = $sigsPartRelic;
        $current->sigsPartOre = $sigsPartOre;
        $current->sigsPartWormholes = $sigsPartWormholes;

        return $current;
        // StatsHelper::statsStartOld($current, $id, $month, $year);
    }

    public static function wormHoleTypeList($id)
    {
        $list = collect([]);
        $wandering = WormholeType::where('wandering', 1)
            ->where('leads_to', '!=', 14)
            ->where('leads_to', '!=', 15)
            ->where('leads_to', '!=', 16)
            ->where('leads_to', '!=', 17)
            ->where('leads_to', '!=', 18)
            ->select(
                'id',
                'wormhole_type',
                'leads_to'
            )
            ->orderBy('wormhole_type', 'ASC')
            ->get();

        $drifter = WormholeType::where('leads_to', 14)
            ->orWhere('leads_to', 15)
            ->orWhere('leads_to', 16)
            ->orWhere('leads_to', 17)
            ->orWhere('leads_to', 18)
            ->select(
                'id',
                'wormhole_type',
                'leads_to'
            )
            ->orderBy('wormhole_type', 'ASC')
            ->get();
        $systems = WormholeStatics::where('system_id', $id)->get();
        foreach ($systems as $system) {
            $yo = $system->staticType()->select(
                'id',
                'wormhole_type',
                'leads_to'
            )->get();

            $list = $list->merge($yo);
        }
        if ($list->count() > 0) {
            $list = $list->sortBy(['wormhole_type']);
            $list->values()->all();
        }

        $list = $list->merge($wandering);

        return ['wormholeTypeList' => $list];
    }

    public static function drifterTypeList()
    {
        $drifter = WormholeType::where('leads_to', 14)
            ->orWhere('leads_to', 15)
            ->orWhere('leads_to', 16)
            ->orWhere('leads_to', 17)
            ->orWhere('leads_to', 18)
            ->select(
                'id',
                'wormhole_type',
                'leads_to'
            )
            ->orderBy('wormhole_type', 'ASC')
            ->get();

        return $drifter;
    }

    public static function whaleNumbersFirst()
    {
        $whaleNumber =
            JoveSystems::where('drifter', 1)->with(['system', 'system.constellation', 'system.region'])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }



    public static function whaleNumbersSolo($id)
    {
        $whaleNumber =
            JoveSystems::where('id', $id)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }

    public static function whaleNumbersSoloSystemID($id)
    {
        $whaleNumber =
            JoveSystems::where('system_id', $id)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }

    public static function whaleNumbersAll()
    {
        $whaleNumber =
            JoveSystems::where('drifter', 1)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->get();

        return $whaleNumber;
    }

    public static function sigsAll()
    {
        $user = FacadesAuth::user();
        if ($user->can('use_reserved_connection')) {
            $data = Signature::where('signature_group_id', '!=', 1)
                ->where('delete', 0)
                ->where(function ($q) {
                    $q->whereNotNull('name')->orWhere('name', '<>', '');
                })
                ->with([
                    'solar_system:region_id,system_id,constellation_id,name',
                    'solar_system.constellation',
                    'solar_system.region',
                    'group',
                    'reserve.user:id,name,main_character_id',
                ])
                ->select(
                    'id',
                    'signature_id',
                    'name_id',
                    'system_id',
                    'type',
                    'signature_group_id',
                    'name',
                    'signal_strength',
                    'life_time',
                    'life_left',
                    'created_at',
                    'updated_at',
                    'report_count',
                    'route_link_p as route_link',
                    'jumps_p as jumps'
                )
                ->get();

            return $data;
        } else {
            $data = Signature::where('signature_group_id', '!=', 1)
                ->where('delete', 0)
                ->where(function ($q) {
                    $q->whereNotNull('name')->orWhere('name', '<>', '');
                })
                ->with([
                    'solar_system:region_id,system_id,constellation_id,name',
                    'solar_system.constellation',
                    'solar_system.region',
                    'group',
                    'reserve.user:id,name,main_character_id',
                ])
                ->select(
                    'id',
                    'signature_id',
                    'name_id',
                    'system_id',
                    'type',
                    'signature_group_id',
                    'name',
                    'signal_strength',
                    'life_time',
                    'life_left',
                    'created_at',
                    'updated_at',
                    'report_count',
                    'route_link',
                    'jumps'
                )
                ->get();

            return $data;
        }
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $permissions
     * 0 = has permissions to use resivered routes
     * 1 = dosnt have permissions to use resivered routes
     */
    public static function sigsSolo($sigID, $permissions)
    {
        if ($permissions == 1) {
            $data = Signature::where('id', $sigID)->with([
                'solar_system:region_id,system_id,constellation_id,name',
                'solar_system.constellation',
                'solar_system.region',
                'group',
                'reserve.user:id,name,main_character_id',
            ])
                ->select(
                    'id',
                    'signature_id',
                    'name_id',
                    'system_id',
                    'type',
                    'signature_group_id',
                    'name',
                    'signal_strength',
                    'life_time',
                    'life_left',
                    'created_at',
                    'updated_at',
                    'report_count',
                    'route_link_p as route_link',
                    'jumps_p as jumps'
                )
                ->first();

            return $data;
        } else {
            $data = Signature::where('id', $sigID)->with([
                'solar_system:region_id,system_id,constellation_id,name',
                'solar_system.constellation',
                'solar_system.region',
                'group',
                'reserve.user:id,name,main_character_id',
            ])
                ->select(
                    'id',
                    'signature_id',
                    'name_id',
                    'system_id',
                    'type',
                    'signature_group_id',
                    'name',
                    'signal_strength',
                    'life_time',
                    'life_left',
                    'created_at',
                    'updated_at',
                    'report_count',
                    'route_link',
                    'jumps'
                )
                ->first();

            return $data;
        }
    }

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = update/add Sig table
     * 2 = delete sig table
     */
    public static function sigsBcastSolo($sigID, $flagNumber)
    {
        if ($flagNumber == 2) {
            $message = $sigID;
        } else {
            $message = Helper::sigsSolo($sigID, 0);
        }
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new SigsUpdate($flag));

        if ($flagNumber == 2) {
            $message = $sigID;
        } else {
            $message = Helper::sigsSolo($sigID, 1);
        }

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new SigspUpdate($flag));
    }
}
