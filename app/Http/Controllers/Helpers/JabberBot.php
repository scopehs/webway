<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;

class JabberBot extends Controller
{
    public static function post($content, $channel = 'pathfinders_report')
    {
        $jabber_url = config('jabber.url');

        // $channel = 'pathfinders_report@conference.goonfleet.com';
        // I don't care about errors.
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $channelFull = $channel.'@conference.goonfleet.com';
        $options = [
            'channel' => $channelFull,
            'payload' => $content,
        ];

        $url = $jabber_url.'/api/webhook';
        $client->post($url, ['body' => json_encode($options)]);
    }
}
