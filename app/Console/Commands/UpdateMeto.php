<?php

namespace App\Console\Commands;

use App\Jobs\MetoJob;
use Illuminate\Console\Command;

class UpdateMeto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:Metro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Steal Metro wormhole data';

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
        $add = rand(10, 30);
        MetoJob::dispatch()->delay(now()->addMinutes($add));
    }
}
