<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\Connections\Connections;
use Illuminate\Console\Command;

class RemoveReserve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:reserve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will remove reserve status off connection if not claimed';

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
        $then30mins = now()->subMinutes(30);
        $connections = Connections::whereIn('type', [2, 4, 5])->where('reserved', 2)->where('updated_at', '<=', $then30mins)->get();
        foreach ($connections as $connection) {
            $connection->update(['reserved' => 0]);
        }

        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Header
        $content = 'Reserverd';

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
            'title'         => 'Reserverd',
            'description'   => 'Reserverd',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
