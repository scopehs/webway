<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Wormholes\WormholeType;

class Wormholes extends Controller
{
    /*
     * Returns a wormhole type id on name query.
     * expects string
     */
    public static function type($name)
    {
        $response = WormholeType::where('wormhole_type', $name)->first();

        return $response->id;
    }

    /*
     * Returns a wormhole life on id query.
     * expects integer
     */
    public static function life($id)
    {
        $response = WormholeType::where('id', $id)->first();

        return $response->life;
    }

    public static function shipSize($id)
    {
        $response = WormholeType::where('id', $id)->first();

        return $response->jump;
    }

    public static function typeLeadsTo($id)
    {
        $response = WormholeType::where('id', $id)->first();

        return $response->leads_to;
    }
}
