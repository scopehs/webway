<?php

namespace App\Jobs;

use App\Models\Scanning\Signature;
use App\Models\SignatureHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class populatecomptedby implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $id;

    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Signature::where('name', null)->update(['name' => null]);
        SignatureHistory::where('name', null)->update(['name' => null]);
        if ($this->type == 1) {
            $sig = Signature::where('id', $this->id)->whereNotNull('name')->first();
        } else {
            $sig = SignatureHistory::where('id', $this->id)->whereNotNull('name')->first();
        }

        $createdByID = $sig->created_by_id;
        $moddedByID = $sig->modified_by_id ?? null;

        if (! $moddedByID) {
            $name = $sig->created_by_name;
            $sig->update(['completed_by_id' => $createdByID, 'completed_by_name' => $name]);
        } else {
            $name = $sig->modified_by_name;
            $sig->update(['completed_by_id' => $moddedByID, 'completed_by_name' => $name]);
        }
        if ($this->type == 1 && $sig->signature_group_id != 1) {
            $id = $sig->id;
            SigRouteJob::dispatch($id)->onQueue('slow');
        }
    }
}
