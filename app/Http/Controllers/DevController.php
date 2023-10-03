<?php

namespace App\Http\Controllers;

use App\Jobs\AllTheStatsPopulateHistoryJob;
use App\Models\SignatureHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DevController extends Controller
{
    public function getHistoryStats($year, $month)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $users = User::all()->pluck('id');
            foreach ($users as $user) {
                AllTheStatsPopulateHistoryJob::dispatch($user, $month, $year)->onQueue('slow');
            }
        }
    }

    public function setDoneByOnHistory($year, $month)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $sigs = SignatureHistory::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereNotIn('created_by_id', [1, 2])
                ->whereNull('completed_by_id')
                ->where('signal_strength', 100)
                ->get();
            foreach ($sigs as $sig) {
                $sig->completed_by_id = $sig->created_by_id;
                $sig->completed_by_name = $sig->created_by_name;
                $sig->save();
            }
        }
    }

    public function fixDrifterName()
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            $sigs = SignatureHistory::whereNotIn('created_by_id', [1, 2])
                ->where('signature_group_id', 1)
                ->whereNull('name')
                ->get();
            foreach ($sigs as $sig) {
                $sig->name = 'Drifter Wormhole';
                $sig->save();
            }
        }
    }
}
