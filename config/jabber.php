<?php

    /*
    |--------------------------------------------------------------------------
    | Platform.sh configuration
    |--------------------------------------------------------------------------
    */

    $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

    return [

        /*
    |--------------------------------------------------------------------------
    | Jabber Bot API URL
    |--------------------------------------------------------------------------
    |
    | Jabber Bot API URL - Post Content to this.
    |
    */

        'url' => env('JABBER_BOT_URL', ($variables && array_key_exists('JABBER_BOT_URL', $variables)) ? $variables['JABBER_BOT_URL'] : null),

    ];
