<?php

namespace App\Http\Controllers;

use App\Events\OverlayUpdate;
use App\Models\System;
use App\Models\Userlogging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class AppController extends Controller
{
    use HasRoles;
    use HasPermissions;

    public function index()
    {
        $check = Auth::user();
        // $url = url()->current();
        $url = URL::full();
        $siteUrl = env('APP_URL', false);
        session(['url' => $url]);

        return view('/home');
    }

    public function siteUrl()
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $siteUrl = env('APP_URL', ($variables && array_key_exists('APP_URL', $variables)) ? $variables['APP_URL'] : null);

        return ['url' => $siteUrl];
    }

    public function updateOverLay($state)
    {
        if (Auth::user()->can('super_admin')) {
            $message = $state;
            $flag = collect([
                'flag' => 1,
                'message' => $message,
            ]);
            broadcast(new OverlayUpdate($flag))->toOthers();
        }
    }

    public function refreshOverLay()
    {
        if (Auth::user()->can('super_admin')) {
            $flag = collect([
                'flag' => 2,
            ]);
            broadcast(new OverlayUpdate($flag))->toOthers();
        }
    }

    public function url(Request $request)
    {
        Userlogging::create(['user_id' => Auth::id(), 'url' => $request->url]);
    }

    public function test()
    {
        $system = System::all();
        //   dd($system);
        $hello = 'YO YO YO';

        return view('/test', compact('hello', 'system'));
    }

    public function saveAllianceData(Request $request)
    {
        dd($request);
    }
}
