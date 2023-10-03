<?php

$variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'gice' => [
        // 'client_id' => env('GOON_CLIENT_ID'),
        'client_id' => env('GOON_CLIENT_ID', ($variables && array_key_exists('GOON_CLIENT_ID', $variables)) ? $variables['GOON_CLIENT_ID'] : 'null'),
        // 'client_secret' => env('GOON_CLIENT_SECRET'),
        'client_secret' => env('GOON_CLIENT_SECRET', ($variables && array_key_exists('GOON_CLIENT_SECRET', $variables)) ? $variables['GOON_CLIENT_SECRET'] : 'null'),
        // 'redirect' => env('GOON_REDIRECT_URL'),
        'redirect' => env('GOON_REDIRECT_URL', ($variables && array_key_exists('GOON_REDIRECT_URL', $variables)) ? $variables['GOON_REDIRECT_URL'] : 'null'),
    ],

    'eveonline' => [
        'client_id' => env('EVEONLINE_CLIENT_ID', ($variables && array_key_exists('EVEONLINE_CLIENT_ID', $variables)) ? $variables['EVEONLINE_CLIENT_ID'] : null),
        'client_secret' => env('EVEONLINE_CLIENT_SECRET', ($variables && array_key_exists('EVEONLINE_CLIENT_SECRET', $variables)) ? $variables['EVEONLINE_CLIENT_SECRET'] : null),
        'redirect' => env('EVEONLINE_CALLBACK_URI', ($variables && array_key_exists('EVEONLINE_CALLBACK_URI', $variables)) ? $variables['EVEONLINE_CALLBACK_URI'] : null),
    ],
];
