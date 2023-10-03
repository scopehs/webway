<?php

namespace App\Jobs;

use App\Events\ScopehUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class AllUserLogsJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $i;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($i)
    {
        $this->i = $i;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $i = $this->i;
        $request = Request::create('/api/getalluserlogs?page='.$i, 'GET');
        $response = Route::dispatch($request);

        $flag = collect([
            'message' => $response,
        ]);

        broadcast(new ScopehUpdate($flag));
    }
}
