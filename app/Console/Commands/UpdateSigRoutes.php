<?php

namespace App\Console\Commands;

use App\Events\StaticUpdate;
use App\Jobs\SigRouteJob;
use App\Jobs\StaticRouteJob;
use App\Models\SavedRoute;
use App\Models\Scanning\Signature;
use App\Models\Wormholes\WormholeStatics;
use Illuminate\Console\Command;

class UpdateSigRoutes extends Command
{
    /**
     * The name and signature of the console command.456456456
     *
     * @var string
     */
    protected $signature = 'update:sigRoutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the jump count for sigs';

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
        $sigs = Signature::whereIn('signature_group_id', [2, 3, 4, 5, 6])
            ->whereNotNull('completed_by_id')
            ->get();
        foreach ($sigs as $sig) {
            $id = $sig->id;
            SigRouteJob::dispatch($id)->onQueue('slow');
        }

        $holes = allBrokenSigIDs();
        // dd($holes);
        foreach ($holes as $sig) {
            $id = $sig->id;
            SigRouteJob::dispatch($id)->onQueue('slow');
        }

        $statics = allStaticSystemIds();
        foreach ($statics as $static) {
            StaticRouteJob::dispatch($static)->onQueue('slow');
        }

        $nonStatic = WormholeStatics::whereNotIn('system_id', $statics)
            ->whereNotNull('jumps')
            ->get();
        foreach ($nonStatic as $stats) {
            if ($stats->link) {
                SavedRoute::whereLink($stats->link)->delete();
                $stats->update([
                    'jumps' => null,
                    'link' => null,
                ]);
                $message = $stats->id;
                $flag = collect([
                    'flag' => 2,
                    'message' => $message,
                ]);
                broadcast(new StaticUpdate($flag));
            }
        }
    }
}
