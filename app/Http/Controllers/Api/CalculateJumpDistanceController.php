<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SDE\SolarSystem;
use Illuminate\Http\Request;

class CalculateJumpDistanceController extends Controller
{
    public function calculate_jump_distance(Request $request)
    {
        $source = $request->source;
        $target = $request->target;

        $first = SolarSystem::where('name', $source)->first();
        $second = SolarSystem::where('name', $target)->first();

        // Calculate Jump Distance

        $distance = sqrt(pow($first->x - $second->x, 2) + pow($first->y - $second->y, 2) + pow($first->z - $second->z, 2)) / 9460000000000000.0;

        $response = [
            'source'        => $source,
            'target'        => $target,
            'lightyears'    => round($distance, 2),
        ];

        return $response;
    }
}
