<?php

namespace App\Jobs;

use App\Models\BrokenConnectionClaim;
use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class cleanConnectionClaimJob implements ShouldQueue
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
        $claims = BrokenConnectionClaim::get();
        foreach ($claims as $claim) {

            if (Signature::whereId($claim->signarture_id)->whereNull('completed_by_id')->count()) {
                break;
            }

            if (Connections::whereNull('completed_user_id')
                ->where(function ($query) use ($claim) {
                    $query->where('target_sig_id', $claim->signarture_id)
                        ->orWhere('source_sig_id', $claim->signarture_id);
                })->count()
            ) {
                break;
            }

            $claim->delete();
        }
    }
}
