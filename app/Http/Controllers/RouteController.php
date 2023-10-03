<?php

namespace App\Http\Controllers;

use App\Jobs\eveStuffJob;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function pannel()
    {
        return view('/home');
    }

    public function eveStuff(Request $request)
    {
        $startSystem = (int) $request['startSystem'];
        $endSystem = $request['endSystem'];

        eveStuffJob::dispatch($startSystem, $endSystem)->onQueue('slow');
    }
}
