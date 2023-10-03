<?php

use App\Models\JoveSystems;
use Illuminate\Support\Facades\Auth;

if (!function_exists('whaleNumbersFirst')) {
    function whaleNumbersFirst()
    {
        $whaleNumber =
            JoveSystems::where('drifter', 1)->with(['system', 'system.constellation', 'system.region'])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }
}

if (!function_exists('whaleNumbersSolo')) {
    function whaleNumbersSolo($id)
    {
        $whaleNumber =
            JoveSystems::where('id', $id)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }
}

if (!function_exists('whaleNumbersSoloSystemID')) {
    function whaleNumbersSoloSystemID($id)
    {
        $whaleNumber =
            JoveSystems::where('system_id', $id)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->first();

        return $whaleNumber;
    }
}


if (!function_exists('whaleNumbersAll')) {
    function whaleNumbersAll()
    {
        $whaleNumber =
            JoveSystems::where('drifter', 1)
            ->with([
                'system',
                'system.constellation',
                'system.region',
                'user:id,name,main_character_id',
                'user.roles:id,name'
            ])
            ->withCount([
                'sigs' => function ($t) {
                    $t->where('created_by_id', 2);
                },
                'sigs AS barbican' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000002);
                },
                'sigs AS conflux' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000004);
                },
                'sigs AS redoubt' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000006);
                },
                'sigs AS sentinel' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000001);
                },
                'sigs AS vidette' => function ($t) {
                    $t->where('created_by_id', 2)
                        ->where('leads_to', 31000003);
                },
            ])
            ->get();

        return $whaleNumber;
    }
}



if (!function_exists('checkPermissions')) {
    function checkPermissions()
    {
        $array =
            [
                'roles' => Auth::user()->roles->pluck('name'),
                'permissions' => Auth::user()->allPermissions,
            ];

        return json_encode($array);
    }
}
