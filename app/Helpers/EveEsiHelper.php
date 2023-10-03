<?php

use App\Models\EVE\Alliances;
use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use App\Models\EVE\TranquiltyStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use utils\EsiHelper\EsiHelper;

if (!function_exists('eveStatus')) {
    function eveStatus()
    {
        $done = 0;
        $up = 0;
        $players = 0;
        $server_version = 0;
        $start_time = 0;

        $response = Http::get('https://esi.evetech.net/latest/status/');
        do {
            if ($response->successful()) {
                $done = 3;
                $data = $response->collect();
                $up = 1;
                $players = $data['players'];
                $server_version = $data['server_version'];
                $start_time = $data['start_time'];
            } else {
                $errorCode = $response->statuus();
                switch ($errorCode) {
                    case 400:
                        $done++;
                        sleep(5);
                        break;
                    case 420:
                        $done++;
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        break;

                    case 500:
                        $done++;
                        sleep(5);
                        break;

                    case 503:
                        $done++;
                        sleep(5);
                        break;

                    case 504:
                        $done++;
                        sleep(5);
                        break;
                }
            }
        } while ($done != 3);

        TranquiltyStatus::updateOrCreate([
            'id' => 1,
        ], [
            'players' => $players,
            'server_version' => $server_version,
            'start_time' => $start_time,
            'esi_up' => $up,
        ]);
    }
}


if (!function_exists('eveGetAndUpdateCharacter')) {
    function eveGetAndUpdateCharacter($charID)
    {

        $done = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/characters/' . $charID . '/?datasource=tranquility');
            if ($response->successful()) {
                $done = 3;
                $data = $response->collect();
                $char = Characters::whereId($charID)->first();
                if ($char) {
                    $char->name = $data['name'];
                    $char->corporation_id = $data['corporation_id'];
                    $char->alliance_id = isset($data['alliance_id']) ? $data['alliance_id'] : null;
                    $char->user_id = Auth::id();
                    $char->save();
                } else {
                    $newChar = new Characters();
                    $newChar->id = $charID;
                    $newChar->user_id = Auth::id();
                    $newChar->name = $data['name'];
                    $newChar->corporation_id = $data['corporation_id'];
                    $newChar->alliance_id = isset($data['alliance_id']) ? $data['alliance_id'] : null;
                    $newChar->save();
                }
                return;
            } else {
                $errorCode = $response->status();
                switch ($errorCode) {
                    case 400:
                        $done++;
                        sleep(5);
                        break;

                    case 401:
                        $done++;
                        sleep(5);
                        break;

                    case 403:
                        $done++;
                        sleep(5);
                        break;

                    case 420:
                        $done++;
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        break;

                    case 500:
                        $done++;
                        sleep(5);
                        break;

                    case 503:
                        $done++;
                        sleep(5);
                        break;

                    case 504:
                        $done++;
                        sleep(5);
                        break;
                }
            }
        } while ($done != 3);

        return;
    }
}

if (!function_exists('eveGetCharacter')) {
    function eveGetCharacter($charID)
    {

        $done = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/characters/' . $charID . '/?datasource=tranquility');
            if ($response->successful()) {
                $done = 3;
                $data = $response->collect();
                return $data;
            } else {
                $errorCode = $response->status();
                switch ($errorCode) {
                    case 400:
                        $done++;
                        sleep(5);
                        break;

                    case 401:
                        $done++;
                        sleep(5);
                        break;

                    case 403:
                        $done++;
                        sleep(5);
                        break;

                    case 420:
                        $done++;
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        break;

                    case 500:
                        $done++;
                        sleep(5);
                        break;

                    case 503:
                        $done++;
                        sleep(5);
                        break;

                    case 504:
                        $done++;
                        sleep(5);
                        break;
                }
            }
        } while ($done != 3);

        return null;
    }
}

if (!function_exists('eveGetAndUpdateAlliance')) {
    function eveGetAndUpdateAlliance($allianceID)
    {
        $done = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/alliances/' . $allianceID . '/?datasource=tranquility');

            if ($response->successful()) {
                $done = 3;
                $allianceInfo = $response->collect();
                Alliances::updateOrCreate(
                    ['id' => $allianceID],
                    [
                        'name' => $allianceInfo->get('name'),
                        'ticker' => $allianceInfo->get('ticker'),
                    ]
                );
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $done++;
            }
        } while ($done != 3);
    }
}

if (!function_exists('eveGetAlliance')) {
    function eveGetAlliance($allianceID)
    {
        $done = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/alliances/' . $allianceID . '/?datasource=tranquility');

            if ($response->successful()) {
                $done = 3;
                $allianceInfo = $response->collect();
                return $allianceInfo;
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $done++;
            }
        } while ($done != 3);

        return null;
    }
}

if (!function_exists('eveSetCharacterWaypoint')) {
    function eveSetCharacterWaypoint($character_id, $system_id, $add_to_beginning, $clear_other_waypoints)
    {

        $refreshToken = EsiHelper::refreshToken($character_id);
        if ($refreshToken) {

            $done = 0;
            do {

                $char = ESITokens::where('character_id', $character_id)->first();
                $token = $char->token;
                $url = 'https://esi.evetech.net/latest/ui/autopilot/waypoint/?add_to_beginning=false&clear_other_waypoints=true&datasource=tranquility&destination_id=' . $system_id . "&token=" . $token;
                $response = Http::post($url);
                if ($response->successful()) {
                    $done = 3;
                    return true;
                } else {
                    $headers = $response->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $done++;
                }
            } while ($done != 3);
            return false;
        }
    }
}
