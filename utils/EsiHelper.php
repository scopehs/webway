<?php

namespace utils\EsiHelper;

use App\Models\EVE\Alliances;
use App\Models\EVE\Corporations;
use App\Models\EVE\ESITokens;
use Illuminate\Support\Facades\Http;

class EsiHelper
{
    public static function checkEve()
    {
        $run = false;
        $statusCheck = false;

        $response = Http::get('https://esi.evetech.net/ping');

        if ($response->successful()) {
            $ping = $response->body();
            if ($ping == 'ok') {
                $pingCheck = true;
            }
        }

        // $response = Http::get('https://esi.evetech.net/latest/status/?datasource=tranquility');
        // if ($response->successful()) {
        //     $data = $response->collect();
        //     if ($data['players'] > 10) {
        //         $statusCheck = true;
        //     }
        // }

        // if ($statusCheck && $pingCheck) {
        //     $run = true;
        // }
        if ($pingCheck) {
            $run = true;
        }

        return $run;
    }

    public static function refreshToken($charid)
    {
        $run = true;
        $char = ESITokens::where('character_id', $charid)->first();
        $refreshToken = $char->refresh_token;
        if ($char->token_refresh <= now()) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $client_id = env('EVEONLINE_CLIENT_ID', ($variables && array_key_exists('EVEONLINE_CLIENT_ID', $variables)) ? $variables['EVEONLINE_CLIENT_ID'] : 'null');
            $client_secret = env('EVEONLINE_CLIENT_SECRET', ($variables && array_key_exists('EVEONLINE_CLIENT_SECRET', $variables)) ? $variables['EVEONLINE_CLIENT_SECRET'] : 'null');
            $code = base64_encode($client_id.':'.$client_secret);
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.$code,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Host' => 'login.eveonline.com',
                'User-Agent' => 'webway eve@lol.com',
            ])->asForm()->post('https://login.eveonline.com/v2/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]);

            if ($response->successful()) {
                $data = $response->collect();
                $newRefreshTime = now()->addMinutes(19);
                $char->update([
                    'token_refresh' => $newRefreshTime,
                    'token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                return $run;
            } else {
                $run = false;

                return $run;
            }
        } else {
            return $run;
        }
    }

    public static function getLocation($charid)
    {
        $done = 0;
        $tokenDead = 0;
        $char = ESITokens::where('character_id', $charid)->first();
        $token = $char->token;
        do {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/characters/'.$charid.'/location/?datasource=tranquility');
            if ($response->successful()) {
                $done = 3;
                $data = $response->collect();
                $systemID = $data['solar_system_id'];

                return $systemID;
            } else {
                $errorCode = $response->status();
                switch ($errorCode) {
                    case 400:
                        $done++;
                        sleep(5);
                        break;

                    case 401:
                        $done++;
                        $tokenDead = 1;
                        sleep(5);
                        break;

                    case 403:
                        $done++;
                        $tokenDead = 1;
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

        return $tokenDead;
    }

    public static function getAlliance($allianceID)
    {
        $done = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/alliances/'.$allianceID.'/?datasource=tranquility');

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

    public static function getCorp($corpID)
    {
        $corpPull = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'webway eve@lol.com',
            ])->get('https://esi.evetech.net/latest/corporations/'.$corpID.'/?datasource=tranquility');
            if ($response->successful()) {
                $corpPull = 3;
                $corpInfo = $response->collect();
                Corporations::updateOrCreate(
                    ['id' => $corpID],
                    [
                        'alliance_id' => $corpInfo->get('alliance_id') ?? null,
                        'name' => $corpInfo->get('name'),
                        'ticker' => $corpInfo->get('ticker'),

                    ]
                );
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $corpPull++;
            }
        } while ($corpPull != 3);
    }
}
