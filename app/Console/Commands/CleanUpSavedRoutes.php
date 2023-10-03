<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\SavedRoute;
use Illuminate\Console\Command;

class CleanUpSavedRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:savedRoutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up routes that were made, but not saved';

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
        $then1hour = now()->subHours(6);
        SavedRoute::where('saved', 0)->where('created_at', '<', $then1hour)->delete();
        SavedRoute::where('user_id', 2)->where('updated_at', '<', $then1hour)->delete();

        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Header
        $content = 'Save Routes clearend';

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
