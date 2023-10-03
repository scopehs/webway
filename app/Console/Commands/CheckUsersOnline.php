<?php

namespace App\Console\Commands;

use App\Events\ChartsUpdate;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UsersCount;
use Illuminate\Console\Command;
use Pusher\Pusher;

class CheckUsersOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:usersonline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks to see if a user is still online, if not will flag them as off.  Also records number online';

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
        activity()->withoutLogs(
            function () {
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
                // convert to associative array for easier consumption
                $user = User::where('online', 1)->first();
                if ($user) {
                    $user->online = 0;
                    $user->save();
                }
                $channels = $response['channels'];
                $channels = array_keys($channels);

                foreach ($channels as $channel) {
                    $part = explode('.', $channel);
                    if ($part[0] == 'private-user') {
                        $activeUser = $part[1];
                        $userCheck = User::where('id', $activeUser)->first();
                        $userCheck->online = 1;
                        $userCheck->save();
                    }
                }

                $onlineChecks = UserActivity::where('done', 0)->get();
                foreach ($onlineChecks as $onlineCheck) {
                    $check = User::where('id', $onlineCheck->user_id)->value('online');
                    if ($check == 0) {
                        $onlineCheck->update(['done' => 1]);
                    }
                }

                $count = User::where('online', 1)->count();
                if ($count) {
                    $new = UsersCount::create(['count' => $count]);
                } else {
                    $new = UsersCount::create(['count' => 0]);
                }

                $newData = UsersCount::where('id', $new->id)->first();
                $count = [
                    $newData->created_at,
                    $newData->count,
                ];

                $message = [
                    'count' => $count,
                    'max' => $newData->count,
                ];
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                ]);
                broadcast(new ChartsUpdate($flag));
            }
        );
    }
}
