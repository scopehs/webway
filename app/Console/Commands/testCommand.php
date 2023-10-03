<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\DiscordBot;
use Illuminate\Console\Command;

class testCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $webhook = 'https://discord.com/api/webhooks/1050907589615030332/PajfdxJslmuF4qNhF9qeXG-vz_maa4HjEjL2NO0QiQH-6gtpHSgVdgR04-oqV-gX8Ubb';

        // Header
        $content = 'I didnt do it';

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
            'title'         => 'PANIC!!!!!!',
            'description'   => 'Nothing to see here',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
