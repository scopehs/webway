<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DiscordBot extends Controller
{
    public static function post($webhook, $content, $embeds)
    {
        /*
         *  'content' => "Message here.",
         *   'embeds' => [
         *       [
         *           'title' => "An awesome new notification!",
         *           'description' => "Discord Webhooks are great!",
         *           'color' => '7506394',
         *       ]
         */

        return Http::post(
            $webhook,
            [
            'content'   => $content,
            'embeds'    => [$embeds],
        ],
        );
    }
}
