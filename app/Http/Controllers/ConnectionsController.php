<?php

namespace App\Http\Controllers;

use App\Events\AllConnectionsUpdate;
use App\Events\ConnectionNotesUpdate;
use App\Events\MappingUpdate;
use App\Events\RouteUpdate;
use App\Jobs\CleanUpSigJob;
use App\Models\ActivityLog;
use App\Models\CharTracking;
use App\Models\ConnectionHistory;
use App\Models\ConnectionRating;
use App\Models\Connections\Connections;
use App\Models\EVE\Characters;
use App\Models\Scanning\Signature;
use App\Models\SDE\Constellation;
use App\Models\SDE\Region;
use App\Models\SDE\SolarSystem;
use App\Models\SignatureHistory;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use utils\Helper\Helper;
use utils\Loghelper\Loghelper;
use utils\RouteHelper\RouteHelper;
use utils\StatsHelper\StatsHelper;

class ConnectionsController extends Controller
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
        if (Auth::user()->can('view_all_connections')) {
            return ['connections' => Connections::where('delete_flag', 0)
                ->whereIn('type', [2, 4, 5])
                ->with([
                    'sourceSig',
                    'sourceSig.wormhole_type',
                    'sourceSig.wormholeInfoLeadsTo',
                    'sourceSig.wormholeInfoMass',
                    'sourceSig.wormholeInfoShipSize',
                    'sourceSig.wormholeInfoTimeTillDeath',
                    'targetSig',
                    'targetSig.wormhole_type',
                    'targetSig.wormholeInfoLeadsTo',
                    'sourceSystem',
                    'sourceSystem.constellation',
                    'sourceSystem.region',
                    'sourceSystem.systemType',
                    'targetSystem',
                    'targetSystem.constellation',
                    'targetSystem.region',
                    'targetSystem.systemType',
                    'type',
                ])
                ->get(),];
        } else {
            return null;
        }
    }

    public function list()
    {
        $user = Auth::user();
        if ($user->can('view_all_connections')) {
            $source = Connections::where('delete_flag', 0)
                ->whereIn('type', [2, 4, 5])
                ->pluck('source_system_id');

            $target = Connections::where('delete_flag', 0)
                ->whereIn('type', [2, 4, 5])
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
                ->select(['name as text', 'region_id as value'])
                ->orderBy('name')
                ->get();

            $constellationList = Constellation::whereIn('constellation_id', $constellationIDs)
                ->select([
                    'name as text',
                    'constellation_id as value',
                ])
                ->orderBy('name')
                ->get();

            return [
                'region' => $regionList,
                'constellation' => $constellationList,
            ];
        } else {
            return null;
        }
    }

    public function reserveConnection($id)
    {
        if (Auth::user()->can('make_reserved_connection')) {
            $connection = Connections::where('id', $id)->first();
            $connection->reserved = 1;
            $connection->reserved_by_user_id = Auth::id();
            $connection->save();

            $flag = collect([
                'flag' => 4,
                'user_id' => Auth::id(),
            ]);
            broadcast(new RouteUpdate($flag));
        } else {
            return null;
        }
    }

    public function removeReserveFromConnection($id)
    {
        if (Auth::user()->can('make_reserved_connection')) {
            $connection = Connections::where('id', $id)->first();
            $connection->reserved = 0;
            $connection->reserved_by_user_id = null;
            $connection->save();

            $flag = collect([
                'flag' => 4,
                'user_id' => Auth::id(),
            ]);
            broadcast(new RouteUpdate($flag));
        } else {
            return null;
        }
    }

    public function addConnection(Request $request)
    {
        $run = false;
        $chars = Characters::where('user_id', Auth::id())->get();
        foreach ($chars as $char) {
            $tracks = CharTracking::where('character_id', $char->id)->get();
            foreach ($tracks as $track) {
                if ($track->current_system_id == $request->current_system_id) {
                    $run = true;
                }
            }
        }

        if (!$run) {
            return null;
        }

        $wormhole_type = null;
        $start = Signature::where('id', $request->sig_id)->first();
        $start->leads_to = $request->current_system_id;
        $start->modified_by_id = $request->modified_by_id;
        $start->modified_by_name = $request->modified_by_name;
        $start->save();

        $start_life_left = $start->life_left;

        if ($start->connection_id) {
            $a = Signature::where('connection_id', $start->connection_id)->where('id', '!=', $start->id)->first();
            if ($a) {
                removeStaticDone($a->id);
                $a->delete();
            }
            Connections::where('id', $start->connection_id)->delete();
        }
        $time_left = now()->addHours(12);
        $check_type = Signature::where('id', $request->sig_id)->value('type');
        if ($check_type != 420) {
            $wormhole_type = 420;
            $time_left = $start->life_left;
        } else {
        }

        $new = new Signature();
        $new->system_id = $request->current_system_id;
        $new->signature_group_id = 1;
        $new->name = 'Unstable Wormhole';
        $new->name_id = '???';
        $new->leads_to = $request->last_system_id;
        $new->life_time = $start->life_time;
        $new->life_left = $time_left;
        $new->signal_strength = 100;
        $new->type = $wormhole_type;
        $new->created_by_id = $request->modified_by_id;
        $new->created_by_name = $request->modified_by_name;
        $new->wormhole_info_leads_to_id = $request->wormhole_info_leads_to_id;
        $new->wormhole_info_mass_id = $request->wormhole_info_mass_id;
        $new->wormhole_info_ship_size_id = $request->wormhole_info_ship_size_id;
        $new->log_helper = 2;
        $new->wormhole_info_time_till_death_id = $request->wormhole_info_time_till_death_id;
        $new->save();

        //# Create Connection

        $connection = new Connections();
        $connection->source_sig_id = $request->sig_id;
        $connection->target_sig_id = $new->id;
        $connection->source_system_id = $request->last_system_id;
        $connection->target_system_id = $request->current_system_id;
        $connection->type = 2;
        $connection->log_helper = 9;
        $connection->save();

        $connectionid = $connection->id;

        $new->connection_id = $connectionid;
        $new->save();

        $start->connection_id = $connectionid;
        $start->save();

        //# Broadcasts
        $message = Helper::trackingSig($request->sig_id);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $request->last_system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $message = Helper::trackingSig($new->id);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $request->current_system_id,
        ]);
        broadcast(new MappingUpdate($flag));

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new AllConnectionsUpdate($flag));

        StatsHelper::allTheStatsBcastSoloID(Auth::id());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createOrUpdate(Request $request, $signature_id)
    {
        //# Based on a user inputting from signature window.

        /*
            $table->foreignId('source_sig_id')->references('id')->on('signatures');
            $table->foreignId('target_sig_id')->references('id')->on('signatures')->nullable();
            $table->foreignId('source_system_id');
            $table->foreignId('target_system_id')->nullable();
        */

        // source_id target_id
        // scopeh 123
        // monty 456

        // 1 - source_id target_id
        // 1 - 123 -
        // 2 - 456 -

        // -- both jump

        // 1 - 123 - 456
        // 2 - 456 - 123

        // -- one jump

        // 1 - 123 - 456
        // 2 - 456 -

        //# Get Signature of Previous System
        $signature = Signature::where('id', $signature_id)->first();

        $previous_system = $signature->system_id;
        $current_system = $request->current_system_id;

        //# Make the connection
        $connection = Connections::where('source_system_id', $previous_system)
            ->where('target_system_id', $current_system)
            ->first();

        if ($connection) {

            // This connect exists so update the target sig id.
        }

        Connections::create($request->all());

        return response()->json(200);
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

    public function getNotesByConnectionID($id)
    {
        $user = Auth::user();
        if ($user->can('view_connection_notes')) {
            $notes = ConnectionRating::where('connection_id', $id)
                ->with(['userMadeby:id,name'])
                ->get();

            return ['notes' => $notes];
        }

        return ['notes' => null];
    }

    public function addConnectionNote(Request $request)
    {
        $new = new ConnectionRating();
        $new->connection_id = $request->connection_id;
        $new->text = $request->text;
        $new->rating = $request->rating;
        $new->user_id = Auth::id();
        $new->log_helper = 44;
        $new->save();
        $flag = collect([
            'flag' => 1,
            'connection_id' => $request->connection_id,
        ]);
        broadcast(new ConnectionNotesUpdate($flag));
    }

    public function deleteConnectionNotes($id)
    {
        $user = Auth::user();
        if ($user->can('delete_connection_notes')) {
            $notes = ConnectionRating::where('id', $id)->first();
            $notes->update(['log_helper' => 45]);
            $connection_id = $notes->connection_id;
            $notes->delete();
            $flag = collect([
                'flag' => 1,
                'connection_id' => $connection_id,
            ]);
            broadcast(new ConnectionNotesUpdate($flag));
        }
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

    public function reportConnection($id)
    {
        $run = true;
        $connection = Connections::where('id', $id)->first();
        $spam = Activity::where('causer_id', Auth::id())->where('subject_id', $id)->where('description', 'Reported Connection as Gone')->count();
        if ($spam > 0) {
            $run = false;
        }
        if (!$run) {
            return null;
        }

        $count = $connection->report_count;
        $newCount = $count + 1;
        $connection->report_count = $newCount;
        $connection->save();

        if ($newCount == 10) {
            $this->deleteConnection($id);
        }
    }

    public function deleteConnection($id)
    {
        $oldSigs = Signature::where('connection_id', $id)->get();
        $oldUserIDs = $oldSigs->unique('created_by_id')->pluck('created_by_id');
        foreach ($oldSigs as $oldSig) {
            if ($oldSig->delete == 0) {
                // $old_id = $oldSig->id;
                // $old_system = $oldSig->system_id;
                CleanUpSigJob::dispatch($oldSig->id);
                // SignatureHistory::updateOrCreate([
                //     'id' => $oldSig->id ?? null
                // ], [
                //     'name_id' => $oldSig->name_id ?? null,
                //     'signature_id' => $oldSig->signature_id ?? null,
                //     'system_id' => $oldSig->system_id ?? null,
                //     'type' => $oldSig->type ?? null,
                //     'signature_group_id' => $oldSig->signature_group_id ?? null,
                //     'name' => $oldSig->name ?? null,
                //     'leads_to' => $oldSig->leads_to ?? null,
                //     'connection_id' => $oldSig->connection_id ?? null,
                //     'signal_strength' => $oldSig->signal_strength ?? null,
                //     'bookmark_syntax' => $oldSig->bookmark_syntax ?? null,
                //     'life_time' => $oldSig->life_time ?? null,
                //     'life_left' => $oldSig->life_left ?? null,
                //     'delete' => $oldSig->delete ?? null,
                //     'created_by_id' => $oldSig->created_by_id ?? null,
                //     'created_by_name' => $oldSig->created_by_name ?? null,
                //     'modified_by_id' => $oldSig->modified_by_id ?? null,
                //     'modified_by_name' => $oldSig->modified_by_name ?? null,
                //     'wormhole_info_ship_size_id' => $oldSig->wormhole_info_ship_size_id ?? null,
                //     'wormhole_info_leads_to_id' => $oldSig->wormhole_info_leads_to_id ?? null,
                //     'wormhole_info_mass_id' => $oldSig->wormhole_info_mass_id ?? null,
                //     'wormhole_info_time_till_death_id' => $oldSig->wormhole_info_time_till_death_id ?? null,
                //     'created_at' => $oldSig->created_at ?? null,
                //     'updated_at' => $oldSig->updated_at ?? null,
                // ]);

                // $nameID = 1;
                // $descriptionID = 37;
                // $subjectTypeID = "App\Models\SignatureHistory";
                // $subjectID = $oldSig->id;
                // $causerTypeID = 1;
                // $causerID = Auth::id();

                // Loghelper::logadd($nameID, $descriptionID, $subjectTypeID, $subjectID, $causerTypeID, $causerID);
                // ActivityLog::where('subject_id', $oldSig->id)->where('subject_type', 'App\Models\Scanning\Signature')->update(['subject_type' => 'App\Models\SignatureHistory']);
                // Signature::where('id', $old_id)->where('delete', 1)->delete();

                // $flag = null;
                // $flag = collect([
                //     'id' => $old_id,
                //     'flag' => 2,
                //     'system_id' => $old_system
                // ]);
                // broadcast(new MappingUpdate($flag));
                // $flag = collect([
                //     'flag' => 1
                // ]);
                // broadcast(new AllConnectionsUpdate($flag));

                // $oldConnection = Connections::where('id', $oldSig->connection_id)->first();

                // if ($oldConnection) {
                //     if ($oldConnection->delete_flag == 1) {
                //         $oldConnection->delete();
                //     } else {
                //         $new =  ConnectionHistory::create([
                //             'id' => $oldConnection->id ?? null,
                //             'source_sig_id' => $oldConnection->source_sig_id ?? null,
                //             'target_sig_id' => $oldConnection->target_sig_id ?? null,
                //             'source_system_id' => $oldConnection->source_system_id ?? null,
                //             'target_system_id' => $oldConnection->target_system_id ?? null,
                //             'type' => $oldConnection->type ?? null,
                //             'delete_flag' => $oldConnection->delete_flag ?? null,
                //             'created_at' => $oldConnection->created_at ?? null,
                //             'updated_at' => $oldConnection->updated_at ?? null,
                //             'completed_user_id' => $oldConnection->completed_user_id ?? null
                //         ]);
                //         $new->update(['id' => $oldConnection->id]);
                //         $nameID = 1;
                //         $descriptionID = 46;
                //         $subjectTypeID = "App\Models\ConnectionHistory";
                //         $subjectID = $oldSig->id;
                //         $causerTypeID = 1;
                //         $causerID = Auth::id();

                //         ActivityLog::where('subject_id', $oldConnection->id)->where('subject_type', 'App\Models\Connections\Connections')->update(['subject_type' => 'App\Models\ConnectionHistory']);
                //         $oldConnection->delete();
                //     }
                // }
            }

            // $oldSig->delete();
        }

        // foreach ($oldUserIDs as $oldUserID) {
        //     StatsHelper::allTheStatsBcastSoloID($oldUserID);
        // }
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

    public function linkConnections()
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $pusher = new Pusher(
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

        $response = $pusher->get('/channels');
        $response = json_decode(json_encode($response), true);
        User::where('online', 1)->update(['online' => 0]);
        $channels = $response['channels'];
        $channels = array_keys($channels);

        foreach ($channels as $channel) {
            // echo $key;
            $part = explode('.', $channel);
            if ($part[0] == 'private-user') {
                $activeUser = $part[1];
                echo $activeUser . "\n";
                User::where('id', $activeUser)->update(['online' => 1]);
                $data = UserActivity::where('user_id', $activeUser)->orderby('created_at', 'desc')->first();
                if ($data) {
                    $data->update(['updated_at' => now()]);
                }
            }
        }
    }
    /*
     * Check if a full connection has been made and broadcat to jabber
     * Check watchlist to see if this is in a hot area.
     */

    public function checkFullConnection($id)
    {
        $connection = Connections::where('id', $id)
            ->whereHas('targetSig', function (Builder $query) {
                $query->where('delete', 0)
                    ->whereNotNull('signature_id')
                    ->whereNotNull('wormhole_info_ship_size_id')
                    ->whereNotNull('wormhole_info_leads_to_id')
                    ->whereNotNull('wormhole_info_mass_id')
                    ->whereNotNull('wormhole_info_time_till_death_id');
            })->count();
    }

    public function getRoute($source, $target)
    {
        $mass = [3];
        $life = [3];
        $size = [4];
        $jump_bridge = true;
        $avoid_thera = false;

        $request_param = [
            'start_system_id'       => $source,
            'finish_system_id'      => $target,
            'mass'                  => $mass,
            'life'                  => $life,
            'size'                  => $size,
            'jump_bridge'           => $jump_bridge,
            'avoid_thera'           => $avoid_thera,
        ];

        $data = RouteHelper::path($request_param);

        return $data;
    }

    public function getReservedConnections()
    {
        $connections = Connections::where('reserved_by_user_id', Auth::id())
            ->select('id', 'source_sig_id', 'target_sig_id')
            ->with([
                'sourceSig:id,system_id,signature_id',
                'sourceSig.solar_system:name,system_id',
                'targetSig:id,system_id,signature_id',
                'targetSig.solar_system:name,system_id',
            ])->get();

        if ($connections) {
            return ['reserved' => $connections];
        } else {
            return ['reserved' => []];
        }
    }

    public function fixConnectionHistory($id)
    {
        $oldConnection = Connections::where('id', $id)->first();

        $new = ConnectionHistory::firstOrCreate([
            'id' => $oldConnection->id,
        ], $oldConnection->replicate()->forceFill([
            'id' => $oldConnection->id,
        ])->toArray());

        Activity::where('subject_id', $oldConnection->id)
            ->where('subject_type', 'App\Models\Connections\Connections')
            ->update(['subject_type' => 'App\Models\ConnectionHistory']);

        $oldConnection->delete();
    }
}
