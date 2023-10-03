<?php

namespace App\Console\Commands;

use App\Jobs\populatecomptedby;
use App\Models\Scanning\Signature;
use App\Models\SignatureHistory;
use Illuminate\Console\Command;

class populateSigsDoneBy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:sigCompletedBy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will go over all the sigs and stamp the completedBy (only run once)';

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
        Signature::where('name', '')->update(['name' => null]);
        SignatureHistory::where('name', '')->update(['name' => null]);
        $sigs = Signature::where('created_by_id', '!=', 1)->whereNotNull('name')->get();
        $sighs = SignatureHistory::where('created_by_id', '!=', 1)->whereNotNull('name')->get();

        foreach ($sigs as $sig) {
            $id = $sig->id;
            $type = 1;
            populatecomptedby::dispatch($id, $type);
        }

        foreach ($sighs as $sig) {
            $id = $sig->id;
            $type = 2;
            populatecomptedby::dispatch($id, $type);
        }
    }
}
