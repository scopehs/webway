<?php

namespace App\Console\Commands\Heartbeat;

use App\Http\Controllers\Helpers\DiscordBot;
use Illuminate\Console\Command;

class HeartbeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heartbeat:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Heartbeat, Schedule to Discord. Hourly.';

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
        $this->discord();
    }

    public function discord()
    {

        // Dev this further to allow for webhooks to be added per function.
        // This is the webhook url, it should be hashed in the DB.
        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Header
        $content = 'Heartbeat.';

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
            'title'         => 'Santity Check',
            'description'   => 'Muh',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
