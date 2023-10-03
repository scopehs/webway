<?php

namespace App\Http\Controllers;

use App\Events\AllConnectionsUpdate;
use App\Events\CharLocationUpdate;
use App\Events\MappingUpdate;
use App\Events\UserUpdate;
use App\Http\Controllers\Helpers\JabberBot;
use App\Jobs\AllTheStatsJob;
use App\Models\ActivityLogSnapShotNew;
use App\Models\ActivityLogSnapShotOld;
use App\Models\CharTracking;
use App\Models\ConnectionHistory;
use App\Models\Connections\Connections;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use App\Models\GasFlavor;
use App\Models\JoveSystems;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use App\Models\SDE\Constellation;
use App\Models\SDE\Region;
use App\Models\SDE\SolarSystem;
use App\Models\SignatureHistory;
use App\Models\SigNote;
use App\Models\SiteSetting;
use App\Models\SystemNote;
use App\Models\User;
use App\Models\UserSigReport;
use App\Models\Wormholes\WormholeStatics;
use App\Models\Zkill;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Pusher\Pusher;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\RequestFailedException;
use Spatie\Activitylog\Models\Activity;
use utils\EsiHelper\EsiHelper;
use utils\Helper\Helper;
use utils\RouteHelper\RouteHelper;
use utils\StatsHelper\StatsHelper;

class testController extends Controller
{
    public function index()
    {
        $to = now()->floorMinute()->format('Y-m-d H:i:s');
        $from = now()->floorMinute()->subMinute()->format('Y-m-d H:i:s');
        $return = $from . ' - ' . $to;

        return $return;
    }

    public function key()
    {
        return User::where('id', 25107)->with('keys')->select('id', 'name')->first();
        // $user = User::find(25107);
        // foreach ($user->keys as $key) {
        //     echo $key->name;
        //     foreach ($key->fleets as $fleet) {
        //         echo $fleet->name;dwsdwdwdw
        //     }
        // }
    }

    public function logreader()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/c26c3ba256e4564ca5a8215dc8e13fe9');
        } else {
            return null;
        }
    }

    public function testLocationWebway($id)
    {
        $data = evestuffLocationCheck($id);
        return response()->json($data);
    }

    public function testNextSystemSigs($id)
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return  Signature::whereId($id)->with("nextSystemSigs:id,system_id,signature_group_id,completed_by_id")->first();
        } else {
            return null;
        }
    }

    public function testESIHelper()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return eveSetCharacterWaypoint(717568371, 30000014, false, true);
        } else {
            return null;
        }
    }

    public function getMissingSigs()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $sigs = Signature::whereNull('completed_by_id')->where('jumps_p', '>', 0)->whereBetween('created_at', [now()->subHours(6), now()->subMinutes(20)])->get();
            return $sigs;
        } else {
            return null;
        }
    }

    public function getMissingStatics()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $systems = SolarSystem::select('system_id')->has("statics")->pluck('system_id');
            return $systems;
        } else {
            return null;
        }
    }

    public function horizon()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            return redirect('/d59b9894932f16ad822f19e04eec9d34be6fdfdcdefc5c1847a4363a7779eebe1860704');
        } else {
            return null;
        }
    }

    public function testMoveStats()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $stat = ActivityLogSnapShotOld::where('id', 28)->first();
            $json = collect([]);

            $loop = 1;
            while ($loop <= 51) {
                $num = $stat[$loop];
                if ($num > 0) {
                    switch ($loop) {
                        case 1:
                            $text = 'Added Drifter Hole';
                            break;
                        case 2:
                            $text = 'Added Leads to Sig';
                            break;
                        case 3:
                            $text = 'Added Leads to System';
                            break;

                        case 4:
                            $text = 'Added Sig';
                            break;

                        case 5:
                            $text = 'Added Sig ID';
                            break;

                        case 9:
                            $text = 'Created Connection';
                            break;

                        case 13:
                            $text = 'Logged In';
                            break;

                        case 16:
                            $text = 'Requested Route';
                            break;

                        case 18:
                            $text = 'Soft Deleted Sig';
                            break;

                        case 20:
                            $text = 'Update Sig';
                            break;

                        case 21:
                            $text = 'Updated Jump Bridges';
                            break;

                        case 22:
                            $text = 'Updated Jove System Info';
                            break;

                        case 24:
                            $text = 'Updated Sig Info';
                            break;

                        case 27:
                            $text = 'Added ESI';
                            break;

                        case 28:
                            $text = 'Reserved Connection';
                            break;

                        case 29:
                            $text = 'Added Hot Area';
                            break;

                        case 30:
                            $text = 'Removed Hot Area';
                            break;

                        case 31:
                            $text = 'Cleared Whale Sig';
                            break;

                        case 32:
                            $text = 'Deleted Whale Connection';
                            break;

                        case 33:
                            $text = 'Added Whale Sig';
                            break;

                        case 34:
                            $text = 'Added Whale Connection';
                            break;

                        case 35:
                            $text = 'Reported Sig Gone';
                            break;

                        case 36:
                            $text = 'Archived Connection';
                            break;

                        case 37:
                            $text = 'Archived Sig';
                            break;

                        case 39:
                            $text = 'Remove Reserved from Connection';
                            break;

                        case 40:
                            $text = 'Added System Note';
                            break;

                        case 41:
                            $text = 'Deleted System Note';
                            break;

                        case 42:
                            $text = 'Added Sig Notes';
                            break;

                        case 43:
                            $text = 'Deleted Sig Notes';
                            break;

                        case 44:
                            $text = 'Added Connection Note';
                            break;

                        case 45:
                            $text = 'Delete Connection Note';
                            break;

                        case 46:
                            $text = 'Archived Connection';
                            break;

                        case 47:
                            $text = 'Reported Connection as Gone';
                            break;

                        case 48:
                            $text = 'Reserved Sig';
                            break;

                        case 49:
                            $text = 'Un-Reserved Sig';
                            break;

                        case 50:
                            $text = 'Reserved Gas Site';
                            break;

                        case 51:
                            $text = 'Un-Reserved Gas Site';
                            break;
                    }

                    $new = $json->put($text, $num);
                }
                $loop++;
            }

            $newSnap = new ActivityLogSnapShotNew();
            $newSnap->id = $stat->id;
            $newSnap->timestamps = false;
            $newSnap->stats = $new;
            $newSnap->created_at = $stat->created_at;
            $newSnap->updated_at = $stat->updated_at;
            $newSnap->save();

            return $newSnap;
        } else {
            return null;
        }
    }

    public function allLogs()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $to = now()->floorMinute()->format('Y-m-d H:i:s');
            $from = now()->floorMinute()->subDays(5)->format('Y-m-d H:i:s');
            $logs = Activity::where('causer_type', 'App\Models\User')
                ->whereBetween('created_at', [$from, $to])
                ->with(['subject', 'causer'])->paginate(1000);

            return ['logs' => $logs];
        } else {
            return null;
        }
    }

    public function testsnap()
    {
        $stat = ActivityLogSnapShotNew::where('id', 194710)->first();
        ActivityLogSnapShotNew::where('id', 194710)->update(['stats->Requested Route' => 20]);
        $data = $stat->stats['Requested Route'];

        return $data;
    }

    public function testLocationChange($location, $charid)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            if ($location != 1) {
                $char = Characters::where('id', $charid)->first();

                if ($location != $char->current_system_id) {
                    Characters::updateOrCreate([
                        'id'                         => $charid,
                    ], [
                        'user_id'                    => $char->user_id,
                        'current_system_id'          => $location,
                        'last_system_id'             => $char->current_system_id,
                    ]);

                    $lastSystemRecord = CharTracking::where('character_id', $charid)->orderByDesc('created_at')->first();
                    // ship_type_id: 11567,
                    if ($lastSystemRecord) {
                        $count = CharTracking::where('character_id', $charid)->count();
                        $jump = $count + 1;

                        CharTracking::create([
                            'character_id' => $charid,
                            'current_system_id' => $location,
                            'last_system_id' => $char->current_system_id,
                            'ship_type_id' => 11567,
                            'count' => $jump,
                        ]);
                    } else {
                        CharTracking::create([
                            'character_id' => $charid,
                            'current_system_id' => $location,
                            'last_system_id' => $char->current_system_id,
                            'ship_type_id' => 11567,
                            'count' => 1,
                        ]);
                    }
                }
            }

            $flag = collect([
                'flag' => 8,
                'char_id' => $charid,
            ]);

            broadcast(new CharLocationUpdate($flag));
        }
    }

    public function testJsonSetting()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            // TestSetting::where('id', 1)->update(['settings->pay_gas_amount', 50]);
            // SiteSetting::truncate();
            // $test = new SiteSetting;
            // $test->save();
            // SiteSetting::where('id', 1)->update(['settings->pay_gas' => 1]);
            // $data = TestSetting::where('id', 1)->first();
            // $pay = $data->settings['pay_gas'];
            // SiteSetting::where('id', 1)->update(['settings->meto_cookie' => '{"cookie": 10,"date": 10}']);
            // SiteSetting::where('id', 1)->update(['settings->metro_cookie->date' => now()]);
            // $num = SiteSetting::where('id', 1)->first();
            // $num = $num->settings['meto_cookie']['date'];

            $pull = SiteSetting::where('id', 1)->first();
            $metroInfo = $pull->settings['metro_cookie'];
            $cookie = $metroInfo['cookie'];
            $date = $metroInfo['date'];
            $twoWeek = now()->subWeeks(2);
            if ($date > $twoWeek) {
                return 'YAY';
            }

            dd($cookie, $date);
        }
    }

    public function getTrackingInfo($charid)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $char = Characters::where('id', $charid)->first();
            $currentSystemID = $char->current_system_id;
            $lastSystemID = $char->last_system_id;

            $route = CharTracking::where('character_id', $charid)
                ->with([
                    'currentSystem.systemType:id,name',
                    'currentSystem.region',
                    'currentSystem.statics.type',
                    'currentSystem.jove',
                    'currentSystem.effect',
                    'lastSystem.systemType:id,name',
                    'lastSystem.region',
                    'lastSystem.statics.type',
                    'lastSystem.jove',
                    'lastSystem.effect',
                    'ship:typeID,typeName',
                    'character:id,name,user_id',
                ])
                ->get();

            $count = $route->count();
            if ($count > 0 && !$currentSystemID) {
                $temp = CharTracking::where('character_id', $charid)->latest()->first();
                $currentSystemID = $temp->current_system_id;
                Characters::where('id', $charid)->update(['current_system_id' => $currentSystemID]);
            }

            if ($count > 0 && !$lastSystemID) {
                $temp = CharTracking::where('character_id', $charid)->latest()->first();
                $lastSystemID = $temp->last_system_id;
                Characters::where('id', $charid)->update(['last_system_id' => $lastSystemID]);
            }

            if ($lastSystemID > 0) {
                $lastSystemUsers = Helper::systemChars($lastSystemID);
                $lastSystemSigs = Helper::allSig($lastSystemID);
                $lastSystemNotes = SystemNote::where(
                    'system_id',
                    $lastSystemID
                )->with(['user:id,name'])
                    ->get();
                $lastSystemSigNotes = SigNote::where('system_id', $lastSystemID)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();

                $lastSystemKills = Zkill::where('solar_system_id', $lastSystemID)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();

                $message = Helper::systemChars($lastSystemID);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $lastSystemID,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            if ($currentSystemID > 0) {
                $currentSystemUsers = Helper::systemChars($currentSystemID);
                $currentSystemSigs = Helper::allSig($currentSystemID);
                $currentSystemNotes = SystemNote::where('system_id', $currentSystemID)
                    ->with(['user:id,name'])
                    ->get();
                $currentSystemSigNotes = SigNote::where('system_id', $currentSystemID)
                    ->with([
                        'user:id,name',
                        'sig:id,signature_id',
                    ])->get();
                $currentSystemKills = Zkill::where('solar_system_id', $currentSystemID)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDay())
                    ->count();
                $wormHoleTypeList = Helper::wormHoleTypeList($currentSystemID);

                $message = Helper::systemChars($currentSystemID);
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'system_id' => $currentSystemID,
                ]);
                broadcast(new MappingUpdate($flag))->toOthers();
            }

            return [
                'route' => $route,
                'lastSystemSigs' => $lastSystemSigs ?? null,
                'lastSystemNotes' => $lastSystemNotes ?? null,
                'lastSystemUsers' => $lastSystemUsers ?? null,
                'lastSystemSigNotes' => $lastSystemSigNotes ?? null,
                'lastSystemKills' => $lastSystemKills ?? 0,
                'lastSystemID' => $lastSystemID ?? null,
                'currentSystemSigs' => $currentSystemSigs ?? null,
                'currentSystemUsers' => $currentSystemUsers ?? null,
                'currentSystemNotes' => $currentSystemNotes ?? null,
                'currentSystemSigNotes' => $currentSystemSigNotes ?? null,
                'currentSystemKills' => $currentSystemKills ?? 0,
                'currentSystemID' => $currentSystemID ?? null,
                'count' => $count,
                'wormholeTypeList' => $wormHoleTypeList ?? null,
            ];
        }
    }

    public function prequal()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            return redirect('/b18e701a9a24feaa538b98059e6ca6e7ef17e8157d8bc8f80a5b23a71f9a2c568611866ce8');
        } else {
            return null;
        }
    }

    public function fixLife()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $sigs = Signature::whereNotNull('connection_id')->where('type', '!=', 420)->get();
            foreach ($sigs as $sig) {
                Signature::where('connection_id', $sig->connection_id)->update(['life_left' => $sig->life_left]);
            }
        }
    }

    public function allthestats()
    {
        $user = Auth::user();
        $authID = Auth::id();
        if ($user->can('super_admin')) {
            $check = Auth::user();
            if ($check->can('view_stats')) {
                $users = User::where('id', '>', 5)->get();
                foreach ($users as $user) {
                    AllTheStatsJob::dispatch($user->id, $authID);
                }
            } else {
                $users = Helper::allTheStatsUsersByID(Auth::id());

                return [
                    'users' => $users,
                ];
            }
        }
    }

    public function lasturl()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            return 'ff';
        }
    }

    public function populateDoneID()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $stat = WormholeStatics::get();
            foreach ($stat as $s) {
                $sig = Signature::whereSignatureGroupId(1)
                    ->whereDelete(0)
                    ->whereNotNull('completed_by_id')
                    ->whereSystemId($s->system_id)
                    ->whereType($s->wormhole_type_id)->first();

                if ($sig) {
                    $s->update(['signature_id' => $sig->id]);
                }
            }
        }
    }

    public function testTokenRefresh($charid)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $data = EsiHelper::refreshToken($charid);
            dd($data);
        }
    }

    public function testEveUp()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $data = EsiHelper::checkEve();
            dd($data);
        }
    }

    public function testShort()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $data = soloShortest(2);
            return $data;
        }
    }

    public function testNewStats($id, $year, $month)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $stat = Helper::allTheStatsUsersUserCostomMonthYear($id, $month, $year);

            return $stat;
        }
    }

    public function testGetStatsAll()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $stat = User::where('id', '>', 5)->with([
                'esi_tokens' => function ($t) {
                    $t->where('active', 1)->select(
                        'id',
                        'user_id',
                        'character_id',
                        'name',
                        'avatar',
                        'tracking'
                    );
                },
                'permissions',
                'roles',
                'statsCurrent',
            ])
                ->select('id', 'name')->get();

            return $stat;
        }
    }

    public function testNewStatsCurrentMonth()
    {
        $user = Auth::user();
        $users = User::all()->pluck('id');
        foreach ($users as $user) {
            Helper::allTheStatsUsersByID($user);
        }
    }

    public function getSystemCount()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $i = 0;
            $sigs = SignatureHistory::whereType(49)->select('id', 'type', 'system_id')->with('solar_system:system_id,name')->groupBy('system_id')->get();
            foreach ($sigs as $sig) {
                $i++;
                echo $sig->solar_system->name . " " . "$i" . "</br>";
            }
        }
    }

    public function lists()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $source = Connections::where('delete_flag', 0)
                ->where('type', 2)
                ->pluck('source_system_id');

            $target = Connections::where('delete_flag', 0)
                ->where('type', 2)
                ->pluck('target_system_id');

            $all = $source->union($target);
            $unique = $all->unique();
            $systemIDs = $unique->values();

            $regionPull = SolarSystem::whereIn('system_id', $systemIDs)
                ->pluck('region_id');

            $regionUnique = $regionPull->unique();
            $regionIDs = $regionUnique->values();
            $constellationPull = SolarSystem::whereIn('system_id', $systemIDs)
                ->pluck('constellation_id');

            $constellationUnique = $constellationPull->unique();
            $constellationIDs = $constellationUnique->values();

            $regionList = Region::whereIn('region_id', $regionIDs)
                ->select(['name as text', 'region_id as value'])->get();

            $constellationList = Constellation::whereIn('constellation_id', $constellationIDs)
                ->select([
                    'name as text',
                    'constellation_id as value',
                ])->get();

            return [
                'region' => $regionList,
                'constellation' => $constellationList,
            ];
        }
    }

    public function getlocation($charid)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $refreshToken = EsiHelper::refreshToken($charid);
            echo $refreshToken;
            if ($refreshToken) {
                $location = EsiHelper::getLocation($charid);
                echo $location;
                if ($location != 1) {
                    $char = Characters::where('id', $charid)->first();

                    Characters::updateOrCreate([
                        'id'                         => $charid,
                    ], [
                        'user_id'                    => $char->user_id,
                        'current_system_id'          => $location,
                    ]);

                    if ($location != $char->current_system_id) {
                        $flag = collect([
                            'flag' => 1,
                            'char_id' => $charid,
                            'systemID' => $location,
                        ]);

                        broadcast(new CharLocationUpdate($flag));
                    }
                } else {
                    $char = Characters::where('id', $charid)->first();
                    $esiChar = ESITokens::where('character_id', $charid)->first();
                    $userID = $char->user_id;
                    $char->delete();
                    $esiChar->delete();

                    $flag = collect([
                        'flag' => 2,
                        'user_id' => $userID,
                    ]);

                    broadcast(new UserUpdate($flag));
                }
            }
        } else {
            return 'no access';
        }
    }

    public function getlocationJobTest($id)
    {
        $charid = $id;
        $run = EsiHelper::checkEve();
        if ($run) {
            $refreshToken = EsiHelper::refreshToken($charid);
            if ($refreshToken) {
                $location = EsiHelper::getLocation($charid);

                dd($location);

                // CheckTable::create(['json' => json_encode($location)]);
                if ($location > 1) {
                    $char = Characters::where('id', $charid)->first();

                    if ($location != $char->current_system_id) {
                        $char = Characters::where('id', $charid)->first();

                        if ($location != $char->current_system_id) {
                            Characters::updateOrCreate([
                                'id'                         => $charid,
                            ], [
                                'user_id'                    => $char->user_id,
                                'current_system_id'          => $location,
                                'last_system_id'             => $char->current_system_id,
                            ]);

                            $lastSystemRecord = CharTracking::where('character_id', $charid)->orderByDesc('created_at')->first();
                            // ship_type_id: 11567,
                            if ($lastSystemRecord) {
                                $count = CharTracking::where('character_id', $charid)->count();
                                $jump = $count + 1;

                                CharTracking::create([
                                    'character_id' => $charid,
                                    'current_system_id' => $location,
                                    'last_system_id' => $char->current_system_id,
                                    'ship_type_id' => 11567,
                                    'count' => $jump,
                                ]);
                            } else {

                                // CharTracking::create([
                                //     'character_id' => $charid,
                                //     'current_system_id' => $location,
                                //     'last_system_id' => $char->current_system_id,
                                //     'ship_type_id' => 11567,
                                //     'count' => 1,
                                // ]);
                            }

                            $flag = collect([
                                'flag' => 8,
                                'char_id' => $charid,
                            ]);

                            // broadcast(new CharLocationUpdate($flag));
                            // $userID = $char->user_id;
                            // $flag = collect([
                            //     'flag' => 3,
                            //     'user_id' => $userID,
                            //     'systemID' => $location
                            // ]);

                            // broadcast(new RouteUpdate($flag));
                        }
                    }
                } else {
                    $char = Characters::where('id', $charid)->first();
                    $esiChar = ESITokens::where('character_id', $charid)->first();
                    $userID = $char->user_id;
                    $char->delete();
                    $esiChar->delete();

                    $flag = collect([
                        'flag' => 2,
                        'user_id' => $userID,
                    ]);

                    broadcast(new UserUpdate($flag));
                }
            }

            echo 'no token';
        }

        echo 'no run';
    }

    public function testjabberPingAgain()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $data = RouteHelper::jabberCheck(2926558);

            return $data;
        }
    }

    public function jabberPingTest()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $jab_message = 'this is a test';
            $channel = 'gas_station';
            JabberBot::post($jab_message, $channel);
        } else {
            return 'go away';
        }
    }

    public function getSystemNotes($id)
    {
        $notes = SystemNote::where('system_id', $id)->with([
            'user:id,name',
        ])->get();

        return [
            'systemNotes' => $notes,
        ];
    }

    public function getMainCharID($name)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return null;
        }
        $url = 'https://esi.evetech.net/latest/search/?categories=character&datasource=tranquility&language=en&search=' . $name . '&strict=true';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($url);

        $char = $response->collect();
        $char = $char->first();

        return $char[0];
    }

    public function charLogs($id, $did)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return null;
        }

        $logs = User::select(['id', 'name'])->where('id', $id)->with([
            'logs' => function ($e) use ($did) {
                $e->where('description_id', $did);
            },
            'esi_tokens:id,user_id,character_id,avatar,active',
            'logs.descriptionType',
            'logs.subject' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Signature::class => [
                        'solar_system:constellation_id,region_id,name,security,system_id',
                        'solar_system.constellation',
                        'solar_system.region',
                        'linked_solar_system:constellation_id,region_id,name,security,system_id',
                        'linked_solar_system.constellation',
                        'linked_solar_system.region',
                        'targetConnection:source_sig_id,target_sig_id',
                        'targetConnection.sourceSig:signature_id',
                        'group',
                    ],
                    SignatureHistory::class => [
                        'solar_system:constellation_id,region_id,name,security,system_id',
                        'solar_system.constellation',
                        'solar_system.region',
                        'linked_solar_system:constellation_id,region_id,name,security,system_id',
                        'linked_solar_system.constellation',
                        'linked_solar_system.region',
                        'targetConnection:source_sig_id,target_sig_id',
                        'targetConnection.sourceSig:signature_id',
                        'group',
                    ],
                    Characters::class => [
                        'corporation:id,name,ticker',
                        'alliance:id,name,ticker',
                        'user:id,name',
                    ],
                    Connections::class => [
                        'sourceSig:id,signature_id',
                        'targetSig:id,signature_id',
                        'sourceSystem:system_id,constellation_id,region_id,name',
                        'targetSystem:system_id,constellation_id,region_id,name',
                        'sourceSystem.constellation',
                        'targetSystem.constellation',
                        'sourceSystem.region',
                        'targetSystem.region',
                        'type',
                    ],
                    ConnectionHistory::class => [
                        'sourceSig:id,signature_id',
                        'targetSig:id,signature_id',
                        'sourceSystem:system_id,constellation_id,region_id,name',
                        'targetSystem:system_id,constellation_id,region_id,name',
                        'sourceSystem.constellation',
                        'targetSystem.constellation',
                        'sourceSystem.region',
                        'targetSystem.region',
                        'type',
                    ],
                    JoveSystems::class => [
                        'system:system_id,constellation_id,region_id,name',
                        'system.constellation',
                        'system.region',
                    ],
                    SavedRoute::class => [
                        'startSystem:system_id,constellation_id,region_id,name',
                        'endSystem:system_id,constellation_id,region_id,name',
                        'startSystem.constellation',
                        'endSystem.constellation',
                        'startSystem.region',
                        'endSystem.region',
                    ],
                ]);
            },
        ])->first();

        // vv$logs = User::select(['id', 'name'])->where('id', $id)->with([
        //     'esi_tokens:id,user_id,character_id,avatar,active',
        //     'logs.descriptionType',
        //     'logs.subject'
        // ])->first();

        return ['userlogs' => $logs];

        return ['logs' => $logs];

        // $id = 10000060;
        // $stations =  Notifications::reconRegionPull($id);
        // foreach ($stations as $station) {
        //     Notifications::reconRegionPullIdCheck($station);
        // }
    }

    public function pusherChannelCheck($id)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return 'go away';
        }
        $user = Characters::where('id', $id)->first();
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $pusherA = new Pusher(
            env('PUSHER_APP_KEY', ($variables && array_key_exists('PUSHER_APP_KEY', $variables)) ? $variables['PUSHER_APP_KEY'] : 'null'),
            env('PUSHER_APP_SECRET', ($variables && array_key_exists('PUSHER_APP_SECRET', $variables)) ? $variables['PUSHER_APP_SECRET'] : 'null'),
            env('PUSHER_APP_ID', ($variables && array_key_exists('PUSHER_APP_ID', $variables)) ? $variables['PUSHER_APP_ID'] : 'null'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER', ($variables && array_key_exists('PUSHER_APP_CLUSTER', $variables)) ? $variables['PUSHER_APP_CLUSTER'] : 'null'),
                'encrypted' => true,
                'useTLS' => true,
                'host' => 'https://sockets.scopeh.co.uk',
                'port' => 443,
                'scheme' => 'https',
            ]
        );

        $response = $pusherA->get('/channels/private-user.' . $user->user_id);
        if (!$response == false) {
            return 'yes';
        }

        return 'no';
    }

    public function eveESI($character_id)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return null;
        }

        // Get ESI Token

        $token = ESITokens::where('character_id', $character_id)
            ->where('active', 1)
            //->where('tracking', '>=', 1)
            ->first();

        $userID = $token->user_id;

        if ($token) {
            $now = Carbon::now();

            $location = $this->getCharacterLocation($character_id, $token->refresh_token, $token->token);

            // if (!property_exists($location, "solar_system_id")) {
            //     ESITokens::where('character_id', $character_id)->delete();
            //     Characters::where('id', $character_id)->delete();
            //     return null;
            // }
            // First API Call, if false, Token has been nuked.

            dd($location);
        }
    }

    public function testSeed()
    {
        $data = GasFlavor::where('id', 25268)->with(['nebulas.locations'])->first();

        return $data;
    }

    public function getCharacterLocation($character_id, $refresh_token, $access_token)
    {

        // Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_location

        $client_id = config('services.eveonline.client_id');
        $secret_key = config('services.eveonline.client_secret');

        $authentication = new EsiAuthentication([
            'client_id'     => $client_id,
            'secret'        => $secret_key,
            'access_token'  => $access_token,
            'refresh_token' => $refresh_token,
            'scopes'        => [
                'esi-location.read_location.v1',
            ],
        ]);

        try {
            $esi = new Eseye($authentication);
            $response = $esi->invoke('get', '/characters/{character_id}/location/', [
                'character_id' => $character_id,
            ]);
        } catch (EsiScopeAccessDeniedException $e) {
            // Remove Token & Character
            Characters::where('id', $character_id)->delete();
            ESITokens::where('character_id', $character_id)->delete();

            return $e;
        } catch (RequestFailedException $e) {
            // Remove Token & Character
            // if($e->getMessage() == "invalid_grant: Invalid refresh token. Character grant missing/expired.")
            // {
            //     // User has pulled their token. Fuck em, remove it.
            //     Characters::where('id', $character_id)->delete();
            //     ESITokens::where('character_id', $character_id)->delete();
            // }
            return $e;
        } catch (Exception $e) {
            return $e;
        }

        return $response;
    }

    public function rc(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            dd($input);
        }
    }

    public function userinfo()
    {
        $user = Auth::user()->name;
        $id = Auth::id();
        $current = User::find($id);
        dd($user, $id, $current);
    }

    public function zkill()
    {
        $url = 'https://redisq.zkillboard.com/listen.php';

        $client = new GuzzleHttpClient();
        $headers = [];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $kills = Utils::jsonDecode($response->getBody(), true);

        foreach ($kills as $kill) {
            foreach ($kill['killmail']['attackers'] as $attacker) {
                if ($attacker['final_blow'] == true) {
                    $attackers_alliance_id = $attacker['alliance_id'] ?? null;
                    $attackers_character_id = $attacker['character_id'];
                    $attackers_corporation_id = $attacker['corporation_id'];
                    $attackers_ship_type_id = $attacker['ship_type_id'];
                }
            }

            $zkill = new Zkill();
            $zkill->id = $kill['killID'];
            $zkill->attackers_alliance_id = $attackers_alliance_id;
            $zkill->attackers_character_id = $attackers_character_id;
            $zkill->attackers_corporation_id = $attackers_corporation_id;
            $zkill->attackers_ship_type_id = $attackers_ship_type_id;
            $zkill->killmail_time = $kill['killmail']['killmail_time'];
            $zkill->victim_alliance_id = $kill['killmail']['victim']['alliance_id'] ?? null;
            $zkill->victim_character_id = $kill['killmail']['victim']['character_id'];
            $zkill->victim_corporation_id = $kill['killmail']['victim']['corporation_id'];
            $zkill->victim_ship_type_id = $kill['killmail']['victim']['ship_type_id'];
            $zkill->solar_system_id = $kill['killmail']['solar_system_id'];
            $zkill->totalValue = $kill['zkb']['totalValue'];
            $zkill->save();
        }
    }

    public function test($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structure/' . $id;
        // $dance = env('RECON_TOKEN', "DANCE");
        $dance = env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2');
        // $dance2 = env('RECON_USER', 'DANCE2');
        $dance2 = env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2');

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data = 'Error, Structure Not Found') {
            // echo "NO STATION";
        } else {
            // echo $dance . " - " . $dance2;
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
        }
    }

    public function testSigDelete($id)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return null;
        }

        $run = true;
        $sig = Signature::where('id', $id)->first();
        // $chars = Characters::where('user_id', Auth::id())->get();
        // foreach ($chars as $char) {
        //     if ($char->current_system_id == $sig->system_id || $char->last_system_id == $sig->system_id) {
        //         $run = true;
        //     }
        // }
        // $spam = Activity::where('causer_id', Auth::id())->where('subject_id', $sig->id)->where('description', 'Reported Sig Gone')->count();
        // if ($spam > 0) {
        //     $run = false;
        // }
        if (!$run) {
            return null;
        }

        $count = $sig->report_count;
        $newCount = $count + 1;

        $sig->report_count = $newCount;
        $sig->log_helper = 35;
        $sig->save();
        echo $newCount;

        UserSigReport::create([
            'user_id' => Auth::id(),
            'signature_id' => $id,
        ]);

        if ($newCount == 10) {
            $this->testsigDone($sig->id);
        }
    }
    public function testsigDone($id)
    {
        $check = Auth::user();
        if (!$check->can('super_admin')) {
            return null;
        }
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
}
