<?php

use utils\EsiHelper\EsiHelper;

if (!function_exists('findShortestPath')) {
    function findShortestPath(&$G, $A, $B, $M)
    {
        $P = [];
        $V = [];
        $R = [trim($A)];

        $A = trim($A);
        $B = trim($B);

        while (count($R) > 0 && $M > 0) {
            $X = trim(array_shift($R));

            foreach ($G[$X] as $Y) {
                $Y = trim($Y);
                if ($Y == $B) {
                    array_push($P, $B);
                    array_push($P, $X);
                    if (count($P) == 2) {
                        if ($P[0] == $B && $P[1] == $A) {
                            return array_reverse($P);
                        }
                    }
                    while ($V[$X] != $A) {
                        array_push($P, trim($V[$X]));
                        $X = $V[$X];
                    }
                    array_push($P, $A);

                    return array_reverse($P);
                }
                if (!array_key_exists($Y, $V)) {
                    $V[$Y] = $X;
                    array_push($R, $Y);
                }
            }
        }

        return $P;
    }
}

if (!function_exists('evestuffLocationCheck')) {
    function evestuffLocationCheck($charid)
    {
        $run = EsiHelper::checkEve();
        if ($run) {
            $refreshToken = EsiHelper::refreshToken($charid);
            if ($refreshToken) {
                $location = EsiHelper::getLocation($charid);
                if ($location > 0) {
                    return [
                        'status' => 'true',
                        'system_id'   => $location,
                    ];
                }
            }
        }

        return [
            'status' => 'false',
            'system_id'   => 0,
        ];
    }
}
