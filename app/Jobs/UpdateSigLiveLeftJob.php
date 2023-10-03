<?php

namespace App\Jobs;

use App\Events\AllConnectionsUpdate;
use App\Events\MappingUpdate;
use App\Models\Scanning\Signature;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use utils\Helper\Helper;

class UpdateSigLiveLeftJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

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
        $sigid = $this->sigID;
        $sig = Signature::where('id', $sigid)->first();
        if ($sig) {
            $lifeLeft = Carbon::now()->diffInHours($sig->life_left);
            if ($lifeLeft <= 4) {
                $sig->update(['wormhole_info_time_till_death_id' => 3]);

                $message = Helper::trackingSig($sig->id);
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
        }
    }
}
