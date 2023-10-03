<?php

namespace App\Console\Commands;

use App\Jobs\MoveOldSnapToNewSnapJob;
use App\Models\ActivityLogSnapShotOld;
use Illuminate\Console\Command;

class MoveOldSnapToNewSnapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:snap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts job to move over snap to new table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oldSnaps = ActivityLogSnapShotOld::orderBy('id', 'desc')->pluck('id');
        foreach ($oldSnaps as $oldSnap) {
            MoveOldSnapToNewSnapJob::dispatch($oldSnap)->onQueue('slow');
        }
    }
}
