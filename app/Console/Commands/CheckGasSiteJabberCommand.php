<?php

namespace App\Console\Commands;

use App\Jobs\CheckGasSiteJabberJob;
use App\Models\Nebula;
use App\Models\Scanning\Signature;
use Illuminate\Console\Command;

class CheckGasSiteJabberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:gasstationjabber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks all gas site that have not been pinged, if they need to be pinged.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hotGas = Nebula::where('jabber', 1)->get();
        $hotNames = $hotGas->pluck('name');
        $sigs = Signature::where('signature_group_id', 4)->where('jabber_ping', 0)->where('delete', 0)->whereIn('name', $hotNames)->get();
        foreach ($sigs as $sig) {
            CheckGasSiteJabberJob::dispatch($sig->id);
        }
    }
}
