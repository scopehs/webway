<?php

namespace App\Console\Commands;

use App\Events\OverlayUpdate;
use App\Models\EveEsiStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class getEsiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:EveEsiStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gets all the info from https://esi.evetech.net/status.json?version=latest';

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
     * Execute the consodwdwdwdwle command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'webway.apps.gnf.lt eve@lol.com',
        ])->get('https://esi.evetech.net/status.json?version=latest');
        $status = $response->collect();
        // dd($status);
        $old = EveEsiStatus::where('route', '/characters/{character_id}/location/')->first();
        EveEsiStatus::truncate();
        foreach ($status as $status) {
            $endpoint = $status['endpoint'];
            $method = $status['method'];
            $stat = $status['status'];
            $route = $status['route'];
            $tag = $status['tags'][0];

            EveEsiStatus::updateOrCreate(
                ['route' => $route, 'method' => $method],
                ['status' => $stat, 'tags' => $tag, 'endpoint' => $endpoint]
            );
        }

        $new = EveEsiStatus::where('route', '/characters/{character_id}/location/')->first();
        if ($new) {
            if ($old->status != $new->status) {
                $this->info('diffrent');
                $message = $new->status;
                $flag = collect([
                    'flag' => 3,
                    'message' => $message,
                ]);
                broadcast(new OverlayUpdate($flag))->toOthers();
            } else {
                $this->info('same');
            }
        }
    }
}
