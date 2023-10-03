<?php

namespace App\Jobs;

use App\Events\CharLocationUpdate;
use App\Events\RouteUpdate;
use App\Events\UserUpdate;
use App\Models\CharTracking;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\EsiHelper\EsiHelper;

class newNewUpdateCharLocationJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $charid = $this->id;
        $run = EsiHelper::checkEve();
        if ($run) {
            $refreshToken = EsiHelper::refreshToken($charid);
            if ($refreshToken) {
                $location = EsiHelper::getLocation($charid);
                // CheckTable::create(['json' => json_encode($location)]);
                if ($location > 0) {
                    $char = Characters::where('id', $charid)->first();

                    if ($location != $char->current_system_id) {
                        $char = Characters::where('id', $charid)->first();

                        if ($location != $char->current_system_id) {
                            activity()->disableLogging();
                            Characters::updateOrCreate([
                                'id'                         => $charid,
                            ], [
                                'user_id'                    => $char->user_id,
                                'current_system_id'          => $location,
                                'last_system_id'             => $char->current_system_id,
                            ]);
                            activity()->enableLogging();

                            $lastSystemRecord = CharTracking::where('character_id', $charid)->orderByDesc('created_at')->first();
                            // ship_type_id: 11567,
                            if ($lastSystemRecord) {
                                $count = CharTracking::where('character_id', $charid)->count();
                                $jump = $count + 1;

                                CharTracking::create([
                                    'character_id' => $charid,
                                    'current_system_id' => $location,
                                    'last_system_id' => $lastSystemRecord->current_system_id,
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

                            $flag = collect([
                                'flag' => 8,
                                'char_id' => $charid,
                            ]);

                            broadcast(new CharLocationUpdate($flag));
                            $userID = $char->user_id;
                            $flag = collect([
                                'flag' => 3,
                                'user_id' => $userID,
                                'systemID' => $location,
                            ]);

                            broadcast(new RouteUpdate($flag));
                        }
                    }
                } else {
                    $char = Characters::where('id', $charid)->first();
                    $userID = $char->user_id;
                    $flag = collect([
                        'flag' => 4,
                        'user_id' => $userID,
                    ]);

                    broadcast(new UserUpdate($flag));
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
        } else {
            $char = Characters::where('id', $charid)->first();
            $userID = $char->user_id;
            $flag = collect([
                'flag' => 4,
                'user_id' => $userID,
            ]);

            broadcast(new UserUpdate($flag));
        }
    }
}
