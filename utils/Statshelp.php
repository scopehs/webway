<?php

namespace utils\StatsHelper;

use App\Jobs\AllTheStatsJob;
use App\Models\User;
use App\Models\UserStat;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use utils\Helper\Helper;

class StatsHelper
{
    use HasRoles;
    use HasPermissions;

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $permissions
     * 0 = has permissions to use resivered routes
     * 1 = dosnt have permissions to use resivered routes
     */
    public static function jabberPing($connection_id)
    {
        return 'Laravel fefefFramework';
    }

    public static function checkStatRow($user_id)
    {
        $updateNow = now();
        $year = $updateNow->year;
        $month = $updateNow->month;

        $updateNowBefore = now()->subMonth();
        $yearBefore = $updateNowBefore->year;
        $monthBefore = $updateNowBefore->month;

        $userBefore = Userstat::where('year', $yearBefore)->where('month', $monthBefore)->where('user_id', $user_id)->first();
        if ($userBefore) {
        } else {
            $userBefore = new UserStat();
            $userBefore->year = $yearBefore;
            $userBefore->month = $monthBefore;
            $userBefore->user_id = $user_id;
            $userBefore->save();
        }

        $user = Userstat::where('year', $year)->where('month', $month)->where('user_id', $user_id)->first();
        if ($user) {
            return $user->id;
        } else {
            $user = new UserStat();
            $user->year = $year;
            $user->month = $month;
            $user->user_id = $user_id;
            $user->save();

            return $user->id;
        }
    }

    public static function checkOldStatRow($user_id, $month, $year)
    {
        $user = Userstat::where('year', $year)->where('month', $month)->where('user_id', $user_id)->first();
        if ($user) {
            return $user->id;
        } else {
            $user = new UserStat();
            $user->year = $year;
            $user->month = $month;
            $user->user_id = $user_id;
            $user->save();

            return $user->id;
        }
    }

    public static function statsStartNew($user_id)
    {
        $statID = StatsHelper::checkStatRow($user_id);
        $current = Helper::allTheStatsUsersByID($user_id);
        $userStat = UserStat::where('id', $statID)->first();
        $stats = $userStat->stats;

        $stats['connectionsAll'] = $current->connectionsAll;
        $stats['connectionsDone'] = $current->connectionsDone;
        $stats['connectionsPart'] = $current->connectionsPart;
        $stats['sigsAll'] = $current->sigsAll;
        $stats['sigsDoneAll'] = $current->sigsDoneAll;
        $stats['sigsPartAll'] = $current->sigsPartAll;
        $stats['sigsAllWormholes'] = $current->sigsAllWormholes;
        $stats['sigsDoneWormholes'] = $current->sigsDoneWormholes;
        $stats['sigsPartWormholes'] = $current->sigsPartWormholes;
        $stats['sigsAllRelic'] = $current->sigsAllRelic;
        $stats['sigsDoneRelic'] = $current->sigsDoneRelic;
        $stats['sigsPartRelic'] = $current->sigsPartRelic;
        $stats['sigsAllData'] = $current->sigsAllData;
        $stats['sigsDoneData'] = $current->sigsDoneData;
        $stats['sigsPartData'] = $current->sigsPartData;
        $stats['sigsAllGas'] = $current->sigsAllGas;
        $stats['sigsDoneGas'] = $current->sigsDoneGas;
        $stats['sigsPartGas'] = $current->sigsPartGas;
        $stats['sigsAllCombat'] = $current->sigsAllCombat;
        $stats['sigsDoneCombat'] = $current->sigsDoneCombat;
        $stats['sigsPartCombat'] = $current->sigsPartCombat;
        $stats['sigsAllOre'] = $current->sigsAllOre;
        $stats['sigsDoneOre'] = $current->sigsDoneOre;
        $stats['sigsPartOre'] = $current->sigsPartOre;
        $stats['sigsAllUnknown'] = $current->sigsAllUnknown;
        $userStat->stats = $stats;
        $userStat->save();

        $data = StatsHelper::allTheStatsCurrentUserID($user_id);

        return $data;
    }

    public static function statsStartNewMassUpdate($user_id)
    {
        $statID = StatsHelper::checkStatRow($user_id);
        $current = Helper::allTheStatsUsersByID($user_id);
        $userStat = UserStat::where('id', $statID)->first();
        $stats = $userStat->stats;

        $stats['connectionsAll'] = $current->connectionsAll;
        $stats['connectionsDone'] = $current->connectionsDone;
        $stats['connectionsPart'] = $current->connectionsPart;
        $stats['sigsAll'] = $current->sigsAll;
        $stats['sigsDoneAll'] = $current->sigsDoneAll;
        $stats['sigsPartAll'] = $current->sigsPartAll;
        $stats['sigsAllWormholes'] = $current->sigsAllWormholes;
        $stats['sigsDoneWormholes'] = $current->sigsDoneWormholes;
        $stats['sigsPartWormholes'] = $current->sigsPartWormholes;
        $stats['sigsAllRelic'] = $current->sigsAllRelic;
        $stats['sigsDoneRelic'] = $current->sigsDoneRelic;
        $stats['sigsPartRelic'] = $current->sigsPartRelic;
        $stats['sigsAllData'] = $current->sigsAllData;
        $stats['sigsDoneData'] = $current->sigsDoneData;
        $stats['sigsPartData'] = $current->sigsPartData;
        $stats['sigsAllGas'] = $current->sigsAllGas;
        $stats['sigsDoneGas'] = $current->sigsDoneGas;
        $stats['sigsPartGas'] = $current->sigsPartGas;
        $stats['sigsAllCombat'] = $current->sigsAllCombat;
        $stats['sigsDoneCombat'] = $current->sigsDoneCombat;
        $stats['sigsPartCombat'] = $current->sigsPartCombat;
        $stats['sigsAllOre'] = $current->sigsAllOre;
        $stats['sigsDoneOre'] = $current->sigsDoneOre;
        $stats['sigsPartOre'] = $current->sigsPartOre;
        $stats['sigsAllUnknown'] = $current->sigsAllUnknown;
        $userStat->stats = $stats;
        $userStat->save();
    }

    public static function statsStartOld($user_id, $month, $year)
    {
        $statID = StatsHelper::checkOldStatRow($user_id, $month, $year);
        $current = Helper::allTheStatsUsersUserCostomMonthYear($user_id, $month, $year);
        $userStat = UserStat::where('id', $statID)->first();
        $stats = $userStat->stats;

        // $statID =   StatsHelper::checkStatRow($user_id);
        // $current = Helper::allTheStatsUsersByID($user_id);
        // $userStat = UserStat::where('id', $statID)->first();
        // $stats = $userStat->stats;

        $stats['connectionsAll'] = $current->connectionsAll;
        $stats['connectionsDone'] = $current->connectionsDone;
        $stats['connectionsPart'] = $current->connectionsPart;
        $stats['sigsAll'] = $current->sigsAll;
        $stats['sigsDoneAll'] = $current->sigsDoneAll;
        $stats['sigsPartAll'] = $current->sigsPartAll;
        $stats['sigsAllWormholes'] = $current->sigsAllWormholes;
        $stats['sigsDoneWormholes'] = $current->sigsDoneWormholes;
        $stats['sigsPartWormholes'] = $current->sigsPartWormholes;
        $stats['sigsAllRelic'] = $current->sigsAllRelic;
        $stats['sigsDoneRelic'] = $current->sigsDoneRelic;
        $stats['sigsPartRelic'] = $current->sigsPartRelic;
        $stats['sigsAllData'] = $current->sigsAllData;
        $stats['sigsDoneData'] = $current->sigsDoneData;
        $stats['sigsPartData'] = $current->sigsPartData;
        $stats['sigsAllGas'] = $current->sigsAllGas;
        $stats['sigsDoneGas'] = $current->sigsDoneGas;
        $stats['sigsPartGas'] = $current->sigsPartGas;
        $stats['sigsAllCombat'] = $current->sigsAllCombat;
        $stats['sigsDoneCombat'] = $current->sigsDoneCombat;
        $stats['sigsPartCombat'] = $current->sigsPartCombat;
        $stats['sigsAllOre'] = $current->sigsAllOre;
        $stats['sigsDoneOre'] = $current->sigsDoneOre;
        $stats['sigsPartOre'] = $current->sigsPartOre;
        $stats['sigsAllUnknown'] = $current->sigsAllUnknown;
        $userStat->stats = $stats;
        $userStat->save();
    }

    public static function allTheStatsCurrentUserID($userID)
    {
        $stat = User::where('id', $userID)->with([
            'esi_tokens' => function ($t) {
                $t->where('active', 1)->select(
                    'id',
                    'user_id',
                    'character_id',
                    'name',
                    'avatar',
                    'tracking'
                );
            },
            'permissions',
            'roles',

        ])->select('id', 'name')->get();
        $stat->each->append('totalisk');

        return $stat;
    }

    public static function allTheStatsCurrentAll()
    {
        $stat = User::where('id', '>', 5)->with([
            'esi_tokens' => function ($t) {
                $t->where('active', 1)->select(
                    'id',
                    'user_id',
                    'character_id',
                    'name',
                    'avatar',
                    'tracking'
                );
            },
            'permissions',
            'roles',

        ])->whereHas('statsCurrent', function (Builder $query) {
            $query->where('stats->sigsAll', '>', 0);
        })->select('id', 'name')->get();
        $stat->each->append('totalisk');

        return $stat;
    }

    public static function allTheStatsLastUserID($userID)
    {
        $stat = User::where('id', $userID)->with([
            'esi_tokens' => function ($t) {
                $t->where('active', 1)->select(
                    'id',
                    'user_id',
                    'character_id',
                    'name',
                    'avatar',
                    'tracking'
                );
            },
            'permissions',
            'roles',

        ])->select('id', 'name')->get();
        $stat->each->append('totaliskhistory');

        return $stat;
    }

    public static function allTheStatsLastAll()
    {
        $stat = User::where('id', '>', 5)->with([
            'esi_tokens' => function ($t) {
                $t->where('active', 1)->select(
                    'id',
                    'user_id',
                    'character_id',
                    'name',
                    'avatar',
                    'tracking'
                );
            },
            'permissions',
            'roles',

        ])->where(function ($q) {
            $q->whereHas('roles', function (Builder $query) {
                $query->where('name', 'PathFinder');
            })->orWhereHas('statsLastMonth', function (Builder $query) {
                $query->where('stats->sigsAll', '>', 0);
            });
        })->select('id', 'name')->get();
        $stat->each->append('totaliskhistory');

        return $stat;
    }

    public static function allTheStatsBcastSoloID($userID)
    {
        AllTheStatsJob::dispatch($userID)->onQueue('slow');
    }
}
