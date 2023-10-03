<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\ActivityLogSnapShotNew;
use App\Models\EVE\Characters;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Console\Command;
use Pusher\Pusher;
use Spatie\Activitylog\Models\Activity;

class SnapShotActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a stapshot of all activity';

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

        User::where('online', 1)->update(['online' => 0]);

        $channels = $response['channels'];
        $channels = array_keys($channels);

        foreach ($channels as $channel) {
            $part = explode('.', $channel);
            if ($part[0] == 'private-user') {
                $activeUser = $part[1];
                User::where('id', $activeUser)->update(['online' => 1]);
            }
        }

        $onlineChecks = UserActivity::where('done', 0)->get();
        foreach ($onlineChecks as $onlineCheck) {
            $check = User::where('id', $onlineCheck->user_id)->value('online');
            if ($check == 0) {
                $onlineCheck->update(['done' => 1]);
            }
        }

        activity()->disableLogging();
        $chars = Characters::whereNot('tracking', 0)->get();
        foreach ($chars as $char) {
            if ($char->esiChar->tracking == 0) {
                echo "here";
                $char->tracking = 0;
                $char->save();
            }
        }

        activity()->enableLogging();

        Activity::whereNull('causer_type')->delete();

        $to = now()->floorMinute()->format('Y-m-d H:i:s');
        $from = now()->floorMinute()->subMinute()->format('Y-m-d H:i:s');
        // $from = now()->floorMinute()->subHours(12)->format('Y-m-d H:i:s');

        $stats = Activity::whereBetween('created_at', [$from, $to])->get();
        $des = $stats->pluck('description');
        $des = $des->unique();
        $des = $des->reject('deleted');
        $des = $des->reject('created');
        $des = $des->reject('updated');
        $json = collect([]);

        foreach ($des as $d) {
            $text = $d;
            $num = $stats->where('description', $text)->count();

            $new = $json->put($text, $num);
        }

        $newSnap = new ActivityLogSnapShotNew();
        $newSnap->timestamps = false;
        $newSnap->stats = $new ?? null;
        $newSnap->created_at = $from;
        $newSnap->updated_at = $from;
        $newSnap->save();

        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Headerdwdw
        $content = 'Activity check';

        // Body
        /*
         *  'content' => "Message here.",
         *   'embeds' => [
         *       [
         *           'title' => "An awesome new notification!",
         *           'description' => "Discord Webhooks are great!",
         *           'color' => '7506394',
         *       ]
        */

        $embeds = [
            'title'         => 'ALL ACTIVITY CHECKED - TEST SERVER',
            'description'   => 'FEFEFEFE',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
