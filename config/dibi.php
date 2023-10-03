<?php

use Cuonggt\Dibi\Http\Middleware\EnsureUpToDateAssets;
use Cuonggt\Dibi\Http\Middleware\EnsureUserIsAuthorized;

$variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

return [

    /*
    |--------------------------------------------------------------------------
    | Dibi Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Dibi will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    // 'path' => env('DIBI_DB_CONNECTION', '/lalalala'),
    'path' => env('DIBI_PATH', ($variables && array_key_exists('DIBI_PATH', $variables)) ? $variables['DIBI_PATH'] : '/lalalala'),
    /*
    |--------------------------------------------------------------------------
    | Dibi Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Dibi route - giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => [
        'web',
        EnsureUserIsAuthorized::class,
        EnsureUpToDateAssets::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connection Name
    |--------------------------------------------------------------------------
    |
    | This is the database connection config name that Dibi uses to connect to
    | your database.
    |
    */

    // 'db_connection' => env('DIBI_DB_CONNECTION'),
    'db_connection' => env('DIBI_DB_CONNECTION', ($variables && array_key_exists('DIBI_DB_CONNECTION', $variables)) ? $variables['DIBI_DB_CONNECTION'] : null),

];
