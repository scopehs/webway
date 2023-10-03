<?php

namespace App\Jobs;

use App\Events\AllConnectionsUpdate;
use App\Events\MappingUpdate;
use App\Http\Controllers\Helpers\Wormholes;
use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use App\Models\SiteSetting;
use App\Models\Wormholes\WormholeType;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use utils\Helper\Helper;

class MetoJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $run = false;
        $pull = SiteSetting::where('id', 1)->first();
        $metroInfo = $pull->settings['metro_cookie'];
        $cookie = $metroInfo['cookie'];
        $date = $metroInfo['date'];
        $twoWeek = now()->subWeeks(2);
        if ($date > $twoWeek) {
            $run = true;
        }
        // Does it exist.
        if ($run) {
            // Is the cookie less than 2 weeks old.
            $response = Http::withCookies(['av_ses' => $cookie], 'evemetro.com')->get('https://evemetro.com/api/data/trig');
            // If we have an empty cookie, we get an empty array.
            if ($response->successful()) {
                $data = $response->collect();
                $connections = $data['connections'];

                if ($connections) {
                    $eveSigs = Signature::where('created_by_name', '=', 'EVE-Scouts')->get();
                    foreach ($eveSigs as $evesig) {
                        $eveSigSystem = $evesig->system_id;
                        $eveSigID = $evesig->id;

                        $flag = collect([
                            'flag' => 2,
                            'id' => $eveSigID,
                            'system_id' => $eveSigSystem,
                        ]);
                        broadcast(new MappingUpdate($flag));

                        $flag = collect([
                            'flag' => 1,
                        ]);
                        broadcast(new AllConnectionsUpdate($flag));
                    }

                    Signature::where('created_by_name', '=', 'Eve-Scouts')->delete();
                    // This happens on the job before.
                    Connections::where('type', 5)->delete();

                    // API Return
                    // {
                    //     "id": 10988,
                    //     "comment": "MEF J145225 K162",
                    //     "pochvenSystemId": 30010141,
                    //     "externalSystemId": 31000581,
                    //     "pochvenSystemName": "Sakenta",
                    //     "externalSystemName": "J145225",
                    //     "pochvenWormholeType": "K162",
                    //     "externalWormholeType": "F216",
                    //     "pochvenSignature": "MEF",
                    //     "externalSignature": "YUX",
                    //     "massCritical": false,
                    //     "timeCritical": false,
                    //     "timeCriticalTime": null,
                    //     "createdTime": "2022-02-02T22:07:30.037Z",
                    //     "updated_at": "2022-02-02T22:07:30.135Z",
                    //     "last_seen": null,
                    //     "already_expired_reports": null
                    // }

                    foreach ($connections as $connection) {

                        // asset data types
                        // sig
                        $source_wormhole_type = $connection['pochvenWormholeType'];
                        $target_wormhole_type = $connection['externalWormholeType'];
                        $source_sig_id = $connection['pochvenSignature'];
                        $target_sig_id = $connection['externalSignature'];
                        $massCritical = $connection['massCritical'];
                        $timeCritical = $connection['timeCritical'];
                        $timeCriticalTime = $connection['timeCriticalTime'];
                        $createdTime = $connection['createdTime'];

                        // connection
                        $source_system_id = $connection['pochvenSystemId'];
                        $target_system_id = $connection['externalSystemId'];

                        // get our system types
                        $sourceSystem = SolarSystem::where('system_id', $source_system_id)->first();
                        $destoSystem = SolarSystem::where('system_id', $target_system_id)->first();

                        $sourceTypePull = $sourceSystem->systemType()->first();
                        $destoTypePull = $destoSystem->systemType()->first();

                        $sourceType = $sourceTypePull->id;
                        $destoType = $destoTypePull->id;

                        // Life
                        if ($timeCritical) {
                            $life = 3; //crtical
                            $start = Carbon::parse($timeCriticalTime);
                            $life_left = $start->addHours(4);
                        } else {
                            $life = 2; //stable
                            if ($source_wormhole_type == 'K162') {
                                $wormtype = $target_wormhole_type;
                            } else {
                                $wormtype = $source_wormhole_type;
                            }
                            $start = Carbon::parse($createdTime);
                            $add = WormholeType::where('wormhole_type', $wormtype)->value('life');
                            $life_left = $start->addHours($add); // value to be added to db
                        }

                        // Mass
                        if ($massCritical) {
                            $mass = 3;
                        } else {
                            $mass = 2; // value added to db.
                        }

                        // our source/target system types.
                        switch ($sourceType) {
                            case 1:
                                $sourceLeadsTo = 1;
                                break;

                            case 2:
                                $sourceLeadsTo = 1;
                                break;

                            case 3:
                                $sourceLeadsTo = 1;
                                break;

                            case 4:
                                $sourceLeadsTo = 2;
                                break;

                            case 5:
                                $sourceLeadsTo = 2;
                                break;

                            case 6:
                                $sourceLeadsTo = 3;
                                break;
                            case 7:
                                $sourceLeadsTo = 4;
                                break;

                            case 8:
                                $sourceLeadsTo = 5;
                                break;
                            case 9:
                                $sourceLeadsTo = 6;
                                break;

                            case 12:
                                $sourceLeadsTo = 8;
                                break;

                            case 25:
                                $sourceLeadsTo = 7;
                                break;

                            default:
                                $sourceLeadsTo = 0;
                                break;
                        }

                        switch ($destoType) {
                            case 1:
                                $destoLeadsTo = 1;
                                break;

                            case 2:
                                $destoLeadsTo = 1;
                                break;

                            case 3:
                                $destoLeadsTo = 1;
                                break;

                            case 4:
                                $destoLeadsTo = 2;
                                break;

                            case 5:
                                $destoLeadsTo = 2;
                                break;

                            case 6:
                                $destoLeadsTo = 3;
                                break;
                            case 7:
                                $destoLeadsTo = 4;
                                break;

                            case 8:
                                $destoLeadsTo = 5;
                                break;
                            case 9:
                                $destoLeadsTo = 6;
                                break;

                            case 12:
                                $destoLeadsTo = 8;
                                break;

                            case 25:
                                $destoLeadsTo = 7;
                                break;

                            default:
                                $destoLeadsTo = 0;
                                break;
                        }

                        //wormhole ship size
                        // FIND THE SIDE THATS NOT A K162 TO GET WORMHOLE DATA
                        if ($source_wormhole_type == 'K162') {
                            $wormInfoTypeName = $target_wormhole_type;
                        } else {
                            $wormInfoTypeName = $source_wormhole_type;
                        }

                        $sourceWormType = Wormholes::type($source_wormhole_type);
                        $destoWormType = Wormholes::type($target_wormhole_type);
                        //$wormInfoTypeName = null;
                        //$wormInfoID = null;

                        $wormInfoID = Wormholes::type($wormInfoTypeName);
                        $wormInfoShipSize = Wormholes::shipSize($wormInfoID);

                        switch ($wormInfoShipSize) {
                            case 5000000:
                                $shipSize = 4;
                                break;

                            case 62000000:
                                $shipSize = 3;
                                break;

                            case 300000000:
                                $shipSize = 2;
                                break;

                            case 375000000:
                                $shipSize = 2;
                                break;

                            case 450000000:
                                $shipSize = 2;
                                break;

                            case 1000000000:
                                $shipSize = 1;
                                break;

                            case 2000000000:
                                $shipSize = 1;
                                break;

                            default:
                                $shipSize = null;
                        }

                        // make the connection
                        $new_connection = Connections::create([
                            'source_system_id' => $source_system_id,
                            'target_system_id' => $target_system_id,
                            'type' => 5,
                            'delete_flag' => 0,
                            'trusted' => 0,
                            'reserved' => 0,
                        ]);

                        // make the signatures
                        $nameID = $source_sig_id.'-'.'evescouts';
                        $sourceSig = Signature::create([
                            'signature_id' => $source_sig_id,
                            'name_id' => $nameID,
                            'system_id' => $source_system_id,
                            'type' => $sourceWormType,
                            'signature_group_id' => 1,
                            'name' =>  'Unstable Wormhole',
                            'leads_to' => $target_system_id,
                            'connection_id' => $new_connection->id,
                            'signal_strength' => 100.00,
                            'life_time' => $createdTime,
                            'life_left' => $life_left,
                            'delete' => 0,
                            'created_by_id' => 1,
                            'created_by_name' => 'EVE-Scouts',
                            'wormhole_info_ship_size_id' => $shipSize,
                            'wormhole_info_leads_to_id' =>  $sourceLeadsTo,
                            'wormhole_info_mass_id' => $mass,
                            'wormhole_info_time_till_death_id' => $life,
                        ]);

                        $nameID = $target_sig_id.'evescouts';
                        $destoSig = Signature::create([
                            'signature_id' => $target_sig_id,
                            'name_id' => $nameID,
                            'system_id' => $target_system_id,
                            'type' => $destoWormType,
                            'signature_group_id' => 1,
                            'name' =>  'Unstable Wormhole',
                            'leads_to' => $source_system_id,
                            'connection_id' => $new_connection->id,
                            'signal_strength' => 100.00,
                            'life_time' => $createdTime,
                            'life_left' => $life_left,
                            'delete' => 0,
                            'created_by_id' => 1,
                            'created_by_name' => 'EVE-Scouts',
                            'wormhole_info_ship_size_id' => $shipSize,
                            'wormhole_info_leads_to_id' =>  $destoLeadsTo,
                            'wormhole_info_mass_id' => $mass,
                            'wormhole_info_time_till_death_id' => $life,
                        ]);

                        $new_connection->update([
                            'source_sig_id' => $sourceSig->id,
                            'target_sig_id' => $destoSig->id,
                        ]);

                        $message = Helper::trackingSig($sourceSig->id);
                        $flag = collect([
                            'flag' => 1,
                            'message' => $message,
                            'system_id' => $source_system_id,
                        ]);
                        broadcast(new MappingUpdate($flag));

                        $message = Helper::trackingSig($destoSig->id);
                        $flag = collect([
                            'flag' => 1,
                            'message' => $message,
                            'system_id' => $target_system_id,
                        ]);
                        broadcast(new MappingUpdate($flag));

                        $flag = collect([
                            'flag' => 1,
                        ]);
                        broadcast(new AllConnectionsUpdate($flag));
                    }
                }
            }
        }
    }
}
