<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogSnapShotNew;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function SigsAddedMins(Request $request)
    {
        $min = $request->mins;
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::where('created_at', '>=', now()->subWeek())->orderBy('created_at')->get()->groupBy(function ($item) use ($min) {
            $date = Carbon::parse($item->created_at);

            return  $date->floorMinutes($min)->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addMinutes($min)->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    public function SigsAddedHour()
    {
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::where('created_at', '>=', now()->subWeek())->orderBy('created_at')->get()->groupBy(function ($item) {
            $date = $item->created_at;

            return  $date->floorHour()->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addHour()->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    public function SigsAddedDay()
    {
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::where('created_at', '>=', now()->subMonth())->orderBy('created_at')->get()->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);

            return  $date->floorDay()->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addDay()->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    public function SigsAddedWeek()
    {
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::where('created_at', '>=', now()->subYear())->orderBy('created_at')->get()->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);

            return  $date->floorWeek()->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addWeek()->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    public function SigsAddedMonth()
    {
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::orderBy('created_at')->get()->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);

            return  $date->floorMonth()->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addMonth()->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    public function SigsAddedYear()
    {
        $userCount = [];
        $sigAddvar = [];
        $sigUpdatevar = [];
        $sigConnectionVar = [];
        $sigInfoUpdateVar = [];
        $sigs = ActivityLogSnapShotNew::orderBy('created_at')->get()->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);

            return  $date->floorYear()->format('Y-m-d H:i:s');
        });

        foreach ($sigs as $key => $sig) {
            $a = null;
            $b = null;
            $userIDs = collect();
            $keyAdd = Carbon::parse($key);
            $keyAdd->addYear()->format('Y-m-d H:i:s');
            $usersA = UserActivity::where('done', 0)->where('created_at', '<=', $keyAdd)->get();
            $usersB = UserActivity::where('done', '!=', 0)->where('created_at', '<=', $keyAdd)->where('updated_at', '>=', $key)->get();

            foreach ($usersA as $userA) {
                $checkID = $userA->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $a++;
                }
            }

            foreach ($usersB as $userB) {
                $checkID = $userB->user_id;

                if (! $userIDs->contains($checkID)) {
                    $userIDs->push($checkID);
                    $b++;
                }
            }

            $total = $a + $b;
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($userCount, $varadd);

            $total = $sig->sum('1') + $sig->sum('4');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigAddvar, $varadd);

            $total = $sig->sum('23') + $sig->sum('20');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigUpdatevar, $varadd);

            $total = $sig->sum('5');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigConnectionVar, $varadd);

            $total = $sig->sum('24');
            if ($total == 0) {
                // $total = null;
            }
            $varadd = [
                $key,
                $total,
            ];

            array_push($sigInfoUpdateVar, $varadd);
        }

        return [
            'usercount' => $userCount,
            'sigAddcount' => $sigAddvar,
            'sigUpdatecount' => $sigUpdatevar,
            'sigConnectioncount' => $sigConnectionVar,
            'sigInfoUpdateCount' => $sigInfoUpdateVar,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified feferesource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
