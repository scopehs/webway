<?php

namespace App\Console\Commands;

use App\Jobs\cleanConnectionClaimJob;
use App\Jobs\CleanUpSigJob;
use App\Jobs\UpdateSigLiveLeftJob;
use App\Models\Scanning\Signature;
use Illuminate\Console\Command;

class CleanUpCommandJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:sigsJob';

    /**
     * The console coxxxxxmmand 00000description.
     *
     * @var string
     */
    protected $description = 'This command will move all "dead" sigs and related connections to the histories tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oldSigs = Signature::where('life_left', '<=', now())->where('created_by_id', '!=', 1)->get();
        foreach ($oldSigs as $oldSig) {
            CleanUpSigJob::dispatch($oldSig->id);
        }

        $sigs = Signature::where('delete', 0)->where('signature_group_id', 1)->where('life_left', '>', now())->get();
        foreach ($sigs as $sig) {
            UpdateSigLiveLeftJob::dispatch($sig->id);
        }

        cleanConnectionClaimJob::dispatch();
    }
}
