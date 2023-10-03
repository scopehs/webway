<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\CharTracking;
use App\Models\EVE\Characters;
use Illuminate\Console\Command;

class CleanRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will clear trhe route history of a char';

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
        $chars = Characters::where('tracking', 0)->get();
        foreach ($chars as $char) {
            $delete = false;
            $then = now()->subHours(5);
            $routes = CharTracking::where('character_id', $char->id)->get();
            foreach ($routes as $route) {
                if ($route->created_at < $then) {
                    $delete = true;
                }
            }

            if ($delete) {
                CharTracking::where('character_id', $char->id)->delete();
            }
        }

        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Header
        $content = 'Routes clearend';

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
            'title'         => 'Routes',
            'description'   => 'Routes',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
