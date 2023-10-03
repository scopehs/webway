<?php

namespace App\Console\Commands\EVEApi;

use App\Events\UserUpdate;
use App\Jobs\newNewUpdateCharLocationJob;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use App\Models\EveEsiStatus;
use Illuminate\Console\Command;
use Pusher\Pusher;

class CharacterLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'character:location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Character Locations';

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
     * Execute the consodddle command.
     *
     * @return int
     */
    public function handle()
    {
        $status = EveEsiStatus::where('route', '/characters/{character_id}/location/')->where('method', 'get')->first();

        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $pusher = new Pusher(
            env('PUSHER_APP_KEY', ($variables && array_key_exists('PUSHER_APP_KEY', $variables)) ? $variables['PUSHER_APP_KEY'] : 'null'),
            env('PUSHER_APP_SECRET', ($variables && array_key_exists('PUSHER_APP_SECRET', $variables)) ? $variables['PUSHER_APP_SECRET'] : 'null'),
            env('PUSHER_APP_ID', ($variables && array_key_exists('PUSHER_APP_ID', $variables)) ? $variables['PUSHER_APP_ID'] : 'null'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER', ($variables && array_key_exists('PUSHER_APP_CLUSTER', $variables)) ? $variables['PUSHER_APP_CLUSTER'] : 'null'),
                'encrypted' => true,
                'useTLS' => true,
                'host' => 'https://sockets.scopeh.co.uk',
                'port' => 443,
                'scheme' => 'https',
            ]
        );

        $response = $pusher->get('/channels');
        $response = json_decode(json_encode($response), true);
        $channels = $response['channels'];
        $channels = array_keys($channels);
        $data = collect([]);
        foreach ($channels as $channel) {
            $part = explode('.', $channel);
            if ($part[0] == 'private-user') {
                $data->push($part[1]);
            }
        }
        ESITokens::whereNotIn('user_id', $data)->update(['tracking' => 0]);
        Characters::whereNotIn('user_id', $data);
        $tokens = ESITokens::where('active', 1)->where('tracking', '>=', 1)->get();
        if ($status->status != 'red') {
            if ($tokens) {
                foreach ($tokens as $character) {
                    $response = $pusher->get('/channels/private-user.' . $character->user_id);
                    if (!$response == false) {
                        newNewUpdateCharLocationJob::dispatch($character->character_id)->onQueue('fast');
                    } else {
                        ESITokens::where('user_id', $character->user_id)->update(['tracking' => 0]);
                        Characters::where('user_id', $character->user_id)
                            ->update([
                                'tracking' => 0,
                                'current_system_id' => null,
                                'last_system_id' => null,
                            ]);
                    }
                }

                $this->info('Dispatched ' . count($tokens) . ' jobs to update characters.');
            } else {
                $this->info('No characters being currently tracked.');
            }
        } else {
            foreach ($tokens as $character) {
                $userID = $character->user_id;
                $flag = collect([
                    'flag' => 4,
                    'user_id' => $userID,
                ]);

                broadcast(new UserUpdate($flag));
            }
        }
    }
}
