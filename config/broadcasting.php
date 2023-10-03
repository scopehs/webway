<?php

$variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */
    // 'default' => env('BROADCAST_DRIVER', 'null'),
    'default' => env('BROADCAST_DRIVER', ($variables && array_key_exists('BROADCAST_DRIVER', $variables)) ? $variables['BROADCAST_DRIVER'] : 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        // 'pusher' => [
        //     'driver' => 'pusher',
        //     'key' => env('PUSHER_APP_KEY'),
        //     'secret' => env('PUSHER_APP_SECRET'),
        //     'app_id' => env('PUSHER_APP_ID'),
        //     'options' => [
        //         'cluster' => env('PUSHER_APP_CLUSTER'),
        //         'useTLS' => true,
        //         'disableStats' => true

        //     ],
        // ],

        // 'pusher' => [
        //     'driver' => 'pusher',
        //     'key' => env('PUSHER_APP_KEY'),
        //     'secret' => env('PUSHER_APP_SECRET'),
        //     'app_id' => env('PUSHER_APP_ID'),
        //     'options' => [
        //         'cluster' => env('PUSHER_APP_CLUSTER'),
        //         'encrypted' => true,
        //         'useTLS' => true,
        //         'host' => 'https://sockets.scopeh.co.uk',
        //         'port' => 443,
        //         'scheme' => 'https',
        //         // 'curl_options' => [
        //         //     CURLOPT_SSL_VERIFYHOST => 0,
        //         //     CURLOPT_SSL_VERIFYPEER => 0,
        //         // ]
        //     ],
        // ],

        'pusher' => [
            'driver' => 'pusher',
            // 'key' => env('PUSHER_APP_KEY'),
            'key' => env('PUSHER_APP_KEY', ($variables && array_key_exists('PUSHER_APP_KEY', $variables)) ? $variables['PUSHER_APP_KEY'] : 'null'),
            // 'secret' => env('PUSHER_APP_SECRET'),
            'secret' => env('PUSHER_APP_SECRET', ($variables && array_key_exists('PUSHER_APP_SECRET', $variables)) ? $variables['PUSHER_APP_SECRET'] : 'null'),
            // 'app_id' => env('PUSHER_APP_ID'),
            'app_id' => env('PUSHER_APP_ID', ($variables && array_key_exists('PUSHER_APP_ID', $variables)) ? $variables['PUSHER_APP_ID'] : 'null'),
            'options' => [
                // 'cluster' => env('PUSHER_APP_CLUSTER'),
                'cluster' => env('PUSHER_APP_CLUSTER', ($variables && array_key_exists('PUSHER_APP_CLUSTER', $variables)) ? $variables['PUSHER_APP_CLUSTER'] : 'null'),
                'encrypted' => true,
                'useTLS' => true,
                'host' => 'https://sockets.scopeh.co.uk',
                'port' => 443,
                'scheme' => 'https',
                // 'curl_options' => [
                //     CURLOPT_SSL_VERIFYHOST => 0,
                //     CURLOPT_SSL_VERIFYPEER => 0,
                // ]
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
