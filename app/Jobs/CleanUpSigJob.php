<?php

namespace App\Jobs;

use App\Events\AllConnectionsUpdate;
use App\Events\MappingUpdate;
use App\Models\ConnectionHistory;
use App\Models\Connections\Connections;
use App\Models\ReserveSig;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use App\Models\SignatureHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Models\Activity;
use utils\Helper\Helper;
use utils\StatsHelper\StatsHelper;

class CleanUpSigJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $sigID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sigID)
    {
        $this->sigID = $sigID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->withoutLogs(function () {
            // ...

            $sigid = $this->sigID;
            $oldSig = Signature::where('id', $sigid)->first();
            if ($oldSig) {
                if ($oldSig->delete == 0) {
                    $old_id = $oldSig->id;
                    $old_system = $oldSig->system_id;

                    SignatureHistory::updateOrCreate([
                        'id' => $oldSig->id ?? null,
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
                    $a =  Signature::where('id', $oldSig->id)->where('delete', 1)->first();
                    if ($a) {
                        removeStaticDone($a->id);
                        $a->delete();
                    }

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

                    $oldConnection = Connections::where('id', $oldSig->connection_id)->first();

                    if ($oldConnection) {
                        if ($oldConnection->delete_flag == 1) {
                            $oldConnection->delete();
                        } else {
                            $new = ConnectionHistory::updateOrCreate([
                                'id' => $oldConnection->id ?? null,
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
                    }
                }
                $oldUserID = $oldSig->created_by_id ?? null;
                SavedRoute::where('link', $oldSig->route_link)->delete();
                SavedRoute::where('link', $oldSig->route_link_p)->delete();
                removeStaticDone($oldSig->id);
                $oldSig->delete();
                $reserves = ReserveSig::where('id', $old_id)->get();
                foreach ($reserves as $r) {
                    $r->delete();
                }
                Helper::sigsBcastSolo($old_id, 2);

                if ($oldUserID) {
                    StatsHelper::allTheStatsBcastSoloID($oldUserID);
                }
            }
        });
    }
}
