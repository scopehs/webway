<?php

namespace App\Http\Controllers;

use App\Events\UserUpdate;
use App\Models\ActiviyDescriptionTypes;
use App\Models\ConnectionHistory;
use App\Models\ConnectionRating;
use App\Models\Connections\Connections;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use App\Models\HotArea;
use App\Models\JoveSystems;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use App\Models\SignatureHistory;
use App\Models\SigNote;
use App\Models\SystemNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->can('view_user_logs')) {
            $users = User::has('esi_tokens')->select(
                ['id as value', 'name as text']
            )->orderBy('name')
                ->get();

            return ['userlist' => $users];
        } else {
            return null;
        }
    }

    public function fullUserList()
    {
        $user = Auth::user();
        if ($user->can('view_user_logs')) {
            $users = User::select(
                ['id as value', 'name as text']
            )->orderBy('name')
                ->get();

            return ['userlist' => $users];
        } else {
            return null;
        }
    }

    public function checkAd()
    {
        $userID = Auth::id();
        $flag = collect([
            'flag' => 3,
            'user_id' => $userID,
        ]);

        broadcast(new UserUpdate($flag));
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

    public static function logingUpdate()
    {
        $user = User::where('id', Auth::id())->first();
        $user->last_logged_in = now();
        $user->save();
    }

    public function logs($id)
    {
        $user = Auth::user();
        if ($user->can('view_user_logs')) {
            $logs = User::select(['id', 'name', 'main_character_id'])->where('id', $id)->with([
                'logs' => function ($e) {
                    $e->orderBy('id', 'DESC');
                },
                'esi_tokens:id,user_id,character_id,avatar,active,name',
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
                            'targetConnection:id,source_sig_id,target_sig_id',
                            'targetConnection.sourceSig:id,signature_id',
                            'group',
                        ],
                        SignatureHistory::class => [
                            'solar_system:constellation_id,region_id,name,security,system_id',
                            'solar_system.constellation',
                            'solar_system.region',
                            'linked_solar_system:constellation_id,region_id,name,security,system_id',
                            'linked_solar_system.constellation',
                            'linked_solar_system.region',
                            'targetConnection:id,source_sig_id,target_sig_id',
                            'targetConnection.sourceSig:id,signature_id',
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
                        HotArea::class => [
                            'system',
                            'constellation',
                            'region',
                        ],

                        SystemNote::class => [
                            'system:system_id,name',
                            'user:id,name',
                        ],

                        SigNote::class => [
                            'system:system_id,name',
                            'user:id,name',
                        ],

                        ConnectionRating::class => [
                            'userMadeby:id,name',
                        ],
                    ]);
                },
            ])->first();

            // $logs = User::select(['id', 'name'])->where('id', $id)->with([
            //     'esi_tokens:id,user_id,character_id,avatar,active',
            //     'logs.descriptionType',
            //     'logs.subject'
            // ])->first();

            $chars = Characters::where('user_id', $id)->select([
                'id',
                'name',
                'corporation_id',
                'alliance_id',
                'current_system_id',
                'last_system_id',
                'tracking',
                'ship_type_id',
            ])
                ->with([
                    'corporation:id,name,ticker',
                    'alliance:id,name,ticker',
                    'currentSystem:system_id,name',
                    'lastSystem:system_id,name',
                    'shipType:typeID,typeName',
                ])
                ->get();

            return [
                'userlogs' => $logs,
                'userlogsChars' => $chars,
            ];
        } else {
            return null;
        }
    }

    public function allLogs($from, $to)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            // $logs = Activity::where('causer_type', 'App\Models\User')
            //     ->whereBetween('created_at', [$from, $to])
            //     ->with([
            //         'user:id,name',
            //         'descriptionType',
            //         'subject' => function (MorphTo $morphTo) {
            //             $morphTo->morphWith([
            //                 Signature::class => [
            //                     'solar_system:constellation_id,region_id,name,security,system_id',
            //                     'solar_system.constellation',
            //                     'solar_system.region',
            //                     'linked_solar_system:constellation_id,region_id,name,security,system_id',
            //                     'linked_solar_system.constellation',
            //                     'linked_solar_system.region',
            //                     'targetConnection:id,source_sig_id,target_sig_id',
            //                     'targetConnection.sourceSig:id,signature_id',
            //                     "group"
            //                 ],
            //                 SignatureHistory::class => [
            //                     'solar_system:constellation_id,region_id,name,security,system_id',
            //                     'solar_system.constellation',
            //                     'solar_system.region',
            //                     'linked_solar_system:constellation_id,region_id,name,security,system_id',
            //                     'linked_solar_system.constellation',
            //                     'linked_solar_system.region',
            //                     'targetConnection:id,source_sig_id,target_sig_id',
            //                     'targetConnection.sourceSig:id,signature_id',
            //                     "group"
            //                 ],

            //                 Characters::class => [
            //                     'corporation:id,name,ticker',
            //                     'alliance:id,name,ticker',
            //                     'user:id,name'
            //                 ],
            //                 Connections::class => [
            //                     'sourceSig:id,signature_id',
            //                     'targetSig:id,signature_id',
            //                     'sourceSystem:system_id,constellation_id,region_id,name',
            //                     'targetSystem:system_id,constellation_id,region_id,name',
            //                     'sourceSystem.constellation',
            //                     'targetSystem.constellation',
            //                     'sourceSystem.region',
            //                     'targetSystem.region',
            //                     'type'
            //                 ],
            //                 ConnectionHistory::class => [
            //                     'sourceSig:id,signature_id',
            //                     'targetSig:id,signature_id',
            //                     'sourceSystem:system_id,constellation_id,region_id,name',
            //                     'targetSystem:system_id,constellation_id,region_id,name',
            //                     'sourceSystem.constellation',
            //                     'targetSystem.constellation',
            //                     'sourceSystem.region',
            //                     'targetSystem.region',
            //                     'type'
            //                 ],
            //                 JoveSystems::class => [
            //                     'system:system_id,constellation_id,region_id,name',
            //                     'system.constellation',
            //                     'system.region',
            //                 ],
            //                 SavedRoute::class => [
            //                     'startSystem:system_id,constellation_id,region_id,name',
            //                     'endSystem:system_id,constellation_id,region_id,name',
            //                     'startSystem.constellation',
            //                     'endSystem.constellation',
            //                     'startSystem.region',
            //                     'endSystem.region',
            //                 ],
            //                 HotArea::class => [
            //                     'system',
            //                     'constellation',
            //                     'region'
            //                 ],

            //                 SystemNote::class => [
            //                     'system:system_id,name',
            //                     'user:id,name'
            //                 ],

            //                 SigNote::class => [
            //                     'system:system_id,name',
            //                     'user:id,name'
            //                 ],

            //                 ConnectionRating::class => [
            //                     'userMadeby:id,name'
            //                 ]
            //             ]);
            //         }
            //     ])->paginate(1000);
            $logs = Activity::where('causer_type', 'App\Models\User')
                ->whereBetween('created_at', [$from, $to])
                ->with(['causer'])->orderby('created_at')->paginate(5000);

            return ['logs' => $logs];
        } else {
            return null;
        }
    }

    public function allLogsCount()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $logs = Activity::where('causer_type', 'App\Models\User')
                ->with([
                    'user:id,name',
                    'descriptionType',
                    'subject' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            Signature::class => [
                                'solar_system:constellation_id,region_id,name,security,system_id',
                                'solar_system.constellation',
                                'solar_system.region',
                                'linked_solar_system:constellation_id,region_id,name,security,system_id',
                                'linked_solar_system.constellation',
                                'linked_solar_system.region',
                                'targetConnection:id,source_sig_id,target_sig_id',
                                'targetConnection.sourceSig:id,signature_id',
                                'group',
                            ],
                            SignatureHistory::class => [
                                'solar_system:constellation_id,region_id,name,security,system_id',
                                'solar_system.constellation',
                                'solar_system.region',
                                'linked_solar_system:constellation_id,region_id,name,security,system_id',
                                'linked_solar_system.constellation',
                                'linked_solar_system.region',
                                'targetConnection:id,source_sig_id,target_sig_id',
                                'targetConnection.sourceSig:id,signature_id',
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
                            HotArea::class => [
                                'system',
                                'constellation',
                                'region',
                            ],

                            SystemNote::class => [
                                'system:system_id,name',
                                'user:id,name',
                            ],

                            SigNote::class => [
                                'system:system_id,name',
                                'user:id,name',
                            ],

                            ConnectionRating::class => [
                                'userMadeby:id,name',
                            ],
                        ]);
                    },
                ])->paginate(1000);
            $count = $logs->lastPage();

            return ['pagecount' => $count];
        } else {
            return null;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLocation($id)
    {
        return ['location' => User::where('id', $id)->value('system_id')];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMessage(Request $request, $id)
    {
        User::where('id', $id)->update($request->all());
    }

    // public function getSeletedChar($id)
    // {
    //     $charID = User::where('id', $id)->select('character_id');
    //     if ($charID) {
    //         return  ['selectedChar' => Characters::where('user_id', $charID)->first()];
    //     } else {
    //         return  [];
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     User::where('id', $id)->update($request->all());
    // }

    public function updateChar(Request $request, $id)
    {
        if (Auth::id() == $id) {
            User::where('id', $id)->update($request->all());
            $char = Characters::where('user_id', $id)->first();
            $char->tracking = 0;
            $char->save();
            activity()->withoutLogs(function () use ($id) {
                ESITokens::where('user_id', $id)->update(['tracking' => 0]);
            });
        } else {
            return null;
        }
    }

    public function descriptionTypesList()
    {
        $user = Auth::user();
        if ($user->can('view_user_logs')) {
            $types = ActiviyDescriptionTypes::select(['id', 'name'])->get();

            return ['types' => $types];
        }
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
