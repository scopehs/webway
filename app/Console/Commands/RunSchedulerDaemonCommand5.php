<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunSchedulerDaemonCommand5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daemon5 {--sleep=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a daemon that will automatically run the Laravel schedule every 5 seconds';

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
     * @return mixed
     */
    public function handle(): void
    {
        while (true) {
            Artisan::call('character:location');
            // Calls have to be before the sleep..
            sleep($this->option('sleep'));

            // Artisan::call('update:notifications');
            // Artisan::call('update:stationnotifications');
            // Artisan::call('update:towers');
            // Artisan::call('clean:coordsheet');

            // Artisan::call('schedule:run >> /dev/null 2>&1');
        }
    }
}
