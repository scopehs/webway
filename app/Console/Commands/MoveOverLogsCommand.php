<?php

namespace App\Console\Commands;

use App\Jobs\MoveOverLogsJob;
use App\Models\ActivityLogOld;
use Illuminate\Console\Command;

class MoveOverLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts job to move over logs to new table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oldLogs = ActivityLogOld::orderBy('id', 'desc')->pluck('id');
        foreach ($oldLogs as $log) {
            MoveOverLogsJob::dispatch($log)->onQueue('slow');
        }
    }
}
