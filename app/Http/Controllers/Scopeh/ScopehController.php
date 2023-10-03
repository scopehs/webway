<?php

namespace App\Http\Controllers\Scopeh;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DiscordBot;

class ScopehController extends Controller
{
    public function discord()
    {

        // This is the webhook url, it should be hashed in the DB.
        $webhook = 'https://discord.com/api/webhooks/895459274476650536/_IBtb1l80oQt0whUOIoGOj_FGqlVfSuR9zArFshoXwVdY3PyhkKGyVaxvAE3FfU5feOn';

        // Header
        $content = 'Test Message Here';

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
            'title'         => 'Awesome test message, may use this on feedback',
            'description'   => 'Webhooks are the dawg',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
