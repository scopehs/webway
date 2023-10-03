<?php

use App\Events\BrokenUpdate;
use App\Events\StaticUpdate;
use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\BrokenConnectionClaim;
use App\Models\BrokenStaticClaim;
use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use App\Models\Wormholes\WormholeStatics;
use Illuminate\Support\Facades\Auth;

if (!function_exists('allBroken')) {
    function allBroken()
    {
        $sixHoursAgo = now()->subHours(6);
        $twentyMinutesAgo = now()->subMinutes(20);
        $sigs = Signature::whereNull('completed_by_id')
            ->where('jumps_p', '>', 0)
            ->whereDelete(0)
            ->whereSignatureGroupId(1)
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
            ->select([
                'created_at',
                'system_id',
                'id',
                'jumps_p',
                'name_id',
                'route_link_p'
            ])
            ->with([
                'solar_system.systemType',
                'brokenClaim',
                'brokenClaim.user:main_character_id,id,name',
                'solar_system:system_id,name,constellation_id,region_id',
                'solar_system.constellation',
                'solar_system.region'
            ])
            ->get();
        $mergedSigs = $sigs;

        $ids = Connections::where('completed_user_id', null)
            ->whereDeleteFlag(0)
            ->where('type', 2)
            ->pluck('target_sig_id', 'source_sig_id')
            ->toArray();
        $count = count($ids);
        if ($count > 0) {
            $ids = array_merge(array_keys($ids), array_values($ids));
            $unique_ids = array_unique($ids);


            $Sigs2 = Signature::whereIn('id', $unique_ids)
                ->whereNull('completed_by_id')
                ->where('jumps_p', '>', 0)
                ->whereDelete(0)
                ->whereSignatureGroupId(1)
                ->whereHas('systemTypeCheck', function ($query) {
                    $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
                })
                ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
                ->select([
                    'created_at',
                    'system_id',
                    'id',
                    'jumps_p',
                    'name_id',
                    'route_link_p'
                ])
                ->with([
                    'solar_system.systemType',
                    'brokenClaim',
                    'brokenClaim.user:main_character_id,id,name',
                    'solar_system:system_id,name,constellation_id,region_id',
                    'solar_system.constellation',
                    'solar_system.region'
                ])
                ->get();

            $mergedSigs = $sigs->merge($Sigs2);
        }

        $sigs3 = Signature::whereDelete(0)
            ->whereSignatureGroupId(1)
            ->whereNull('connection_id')
            ->where('jumps_p', '>', 0)
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->whereSignatureGroupId(1)
            ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
            ->select([
                'created_at',
                'system_id',
                'id',
                'jumps_p',
                'name_id',
                'route_link_p'
            ])
            ->with([
                'solar_system.systemType',
                'brokenClaim',
                'brokenClaim.user:main_character_id,id,name',
                'solar_system:system_id,name,constellation_id,region_id',
                'solar_system.constellation',
                'solar_system.region'
            ])
            ->get();
        if ($sigs3) {
            $mergedSigs = $mergedSigs->merge($sigs3);
        }

        return $mergedSigs;
    }
}

if (!function_exists('allBrokenSigIDs')) {
    function allBrokenSigIDs()
    {
        $sixHoursAgo = now()->subHours(6);
        $twentyMinutesAgo = now()->subMinutes(20);
        $sigs = Signature::whereNull('completed_by_id')
            ->whereDelete(0)
            ->whereSignatureGroupId(1)
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
            ->get();
        $mergedSigs = $sigs;


        $ids = Connections::where('completed_user_id', null)
            ->whereDeleteFlag(0)
            ->where('type', 2)
            ->pluck('target_sig_id', 'source_sig_id')
            ->toArray();
        $count = count($ids);
        if ($count > 0) {
            $ids = array_merge(array_keys($ids), array_values($ids));
            $unique_ids = array_unique($ids);


            $Sigs2 = Signature::whereIn('id', $unique_ids)->whereNull('completed_by_id')
                ->whereDelete(0)
                ->whereSignatureGroupId(1)
                ->whereHas('systemTypeCheck', function ($query) {
                    $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
                })
                ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
                ->get();

            $mergedSigs = $sigs->merge($Sigs2);
        }

        $sigs3 = Signature::whereDelete(0)
            ->whereSignatureGroupId(1)
            ->whereNull('connection_id')
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->get();
        if ($sigs3) {
            $mergedSigs = $mergedSigs->merge($sigs3);
        }

        return $mergedSigs;
    }
}

if (!function_exists('soloBroken')) {
    function soloBroken($id)
    {
        $sixHoursAgo = now()->subHours(6);
        $twentyMinutesAgo = now()->subMinutes(20);
        $sigs = Signature::whereId($id)
            ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
            ->select([
                'created_at',
                'system_id',
                'id',
                'jumps_p',
                'name_id',
                'route_link_p'
            ])
            ->with([
                'solar_system.systemType',
                'brokenClaim',
                'brokenClaim.user:main_character_id,id,name',
                'solar_system:system_id,name,constellation_id,region_id',
                'solar_system.constellation',
                'solar_system.region'
            ])
            ->first();


        return $sigs;
    }
}

if (!function_exists('soloBrokenCheck')) {
    function soloBrokenCheck($id)
    {
        $sixHoursAgo = now()->subHours(6);
        $twentyMinutesAgo = now()->subMinutes(20);
        $sig = Signature::whereId($id)
            ->whereBetween('created_at', [$sixHoursAgo, $twentyMinutesAgo])
            ->whereSignatureGroupId(1)
            ->where('jumps_p', '>', 0)
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->select([
                'created_at',
                'system_id',
                'id',
                'jumps_p',
                'name_id',
                'route_link_p'
            ])
            ->with([
                'solar_system.systemType',
                'brokenClaim',
                'brokenClaim.user:main_character_id,id,name',
                'solar_system:system_id,name,constellation_id,region_id',
                'solar_system.constellation',
                'solar_system.region'
            ])
            ->first();


        if ($sig) {

            $message = $sig;
            $flag = collect([
                'flag' => 3,
                'message' => $message,
            ]);

            broadcast(new BrokenUpdate($flag));
        }
    }
}

if (!function_exists('allStaticSystemIds')) {
    function allStaticSystemIds()
    {
        $staticIds = WormholeStatics::whereHas('systemTypeCheck', function ($query) {
            $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
        })->select('system_id')->distinct()->pluck('system_id');

        $systemIds = Signature::whereHas('systemTypeCheck', function ($query) {
            $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
        })->pluck('system_id')->unique();

        $ids = $staticIds->intersect($systemIds);


        return $ids;
    }
}

if (!function_exists('allStaticMissing')) {
    function allStaticMissing()
    {
        $statics = WormholeStatics::whereNotNull('jumps')
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->whereDoesntHave('signature')
            ->with(['system', 'staticType', 'claim'])
            ->get();
        return $statics;
    }
}

if (!function_exists('soloStaticMissing')) {
    function soloStaticMissing($id)
    {
        $statics = WormholeStatics::whereId($id)
            ->whereNotNull('jumps')
            ->whereHas('systemTypeCheck', function ($query) {
                $query->whereNotIn('system_type_id', [14, 15, 16, 17, 18]);
            })
            ->whereDoesntHave('signature')
            ->with(['system', 'staticType', 'claim'])
            ->first();
        return $statics;
    }
}

if (!function_exists('removeStaticDone')) {
    function removeStaticDone($sigId)
    {
        $static = WormholeStatics::whereSignatureId($sigId)->first();
        if ($static) {
            $static->signature_id = 0;
            $static->save();
            BrokenStaticClaim::whereWormholeStaticId($static->id)->delete();
        }
        $flag = collect([
            'flag' => 2,
            'message' => $sigId,
        ]);

        broadcast(new BrokenUpdate($flag));
    }
}


if (!function_exists('holeDone')) {
    function holeDone($sigID)
    {
        $check = Signature::whereId($sigID)->whereSignatureGroupId(1)
            ->where(function ($query) {
                $query->whereNotNull('wormhole_info_ship_size_id')
                    ->whereNotNull('wormhole_info_leads_to_id')
                    ->whereNotNull('wormhole_info_mass_id')
                    ->whereNotNull('wormhole_info_time_till_death_id');
            })
            ->whereNull('completed_by_id')
            ->first();


        if ($check) {
            $check->update([
                'completed_by_id' => Auth::id(),
                'completed_by_name' => Auth::user()->name
            ]);



            $fixed = checkIfSigUnBroken($check->id);
            if ($fixed) {
                $message = $check->id;
                BrokenConnectionClaim::whereId($message)
                    ->delete();

                $flag = collect([
                    'flag' => 2,
                    'message' => $message,
                ]);

                broadcast(new BrokenUpdate($flag));
            }



            $static = WormholeStatics::whereSystemId($check->system_id)
                ->whereWormholeTypeId($check->type)->first();
            if ($static) {
                $static->update(['signature_id' => $check->id]);
                $message = $static->id;
                $flag = collect([
                    'flag' => 2,
                    'message' => $message,
                ]);

                broadcast(new StaticUpdate($flag));
            }
        }

        checkIfConnectionUnBroken($sigID);
    }
}


if (!function_exists('checkIfSigUnBroken')) {
    function checkIfSigUnBroken($sigId)
    {

        $fixed = false;

        $checkSig = Signature::whereId($sigId)
            ->whereNotNull('completed_by_id')
            ->whereDelete(0)
            ->whereSignatureGroupId(1)
            ->whereNotNull('connection_id')
            ->first();

        if ($checkSig) {
            $fixed = true;
        }


        $connectionCheck = Connections::whereNull('completed_user_id')
            ->whereDeleteFlag(0)
            ->whereType(2)
            ->where(function ($q) use ($sigId) {
                $q->where('target_sig_id', $sigId)
                    ->orWhere('source_sig_id', $sigId);
            })->first();

        if ($connectionCheck) {
            return false;
        }


        return $fixed;
    }
}

if (!function_exists('checkIfConnectionUnBroken')) {
    function checkIfConnectionUnBroken($sigId)
    {


        $connectionCheck =
            Connections::whereNotNull('completed_user_id')
            ->whereDeleteFlag(0)
            ->whereType(2)
            ->where(function ($q) use ($sigId) {
                $q->where('target_sig_id', $sigId)
                    ->orWhere('source_sig_id', $sigId);
            })->first();

        if ($connectionCheck) {
            $sigID1 = $connectionCheck->source_sig_id;
            $sigID2 = $connectionCheck->target_sig_id;

            BrokenConnectionClaim::WhereIn('id', [$sigID1, $sigID2])
                ->delete();

            $flag = collect([
                'flag' => 2,
                'message' => $sigID1,
            ]);

            broadcast(new BrokenUpdate($flag));
            $flag = collect([
                'flag' => 2,
                'message' => $sigID2,
            ]);

            broadcast(new BrokenUpdate($flag));
        }
    }
}


if (!function_exists('drifterAdded')) {
    function drifterAdded($sigId)
    {
        // Get the signature where the Drifter hole was found
        $sig = Signature::whereId($sigId)->first();

        // Check if the signature was found in one of the specified regions
        if (in_array($sig->solar_system->region_id, [10000063, 10000050, 10000060])) {
            // Set the Discord webhook URL
            $webhook = 'https://discord.com/api/webhooks/1050907589615030332/PajfdxJslmuF4qNhF9qeXG-vz_maa4HjEjL2NO0QiQH-6gtpHSgVdgR04-oqV-gX8Ubb';

            // Set the message title and body
            $content = "Rolling time";
            $text = 'Difter hole has been found in ' . $sig->solar_system->region->name . ' - ' . $sig->solar_system->constellation->name . ' - ' . $sig->solar_system->name;

            // Set the embed information
            $embeds = [
                'title'         => 'Drifter Hole Has Been Reported!!',
                'description'   => $text,
                'color'         => '7506394',
            ];

            // Post the message and embed to the Discord channel
            DiscordBot::post($webhook, $content, $embeds);
        }
    }
}
