<?php

namespace App\Http\Controllers;

use App\Models\EVE\ESITokens;
use App\Models\EveEsiStatus;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use utils\Helper\Helper;
use utils\StatsHelper\StatsHelper;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    use HasRoles;
    use HasPermissions;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }

    public function handleProviderCallback()
    {
        $flag = 0;
        $userGice = Socialite::with('gice')->user();
        // dd($userGice);
        $check = User::where('id', $userGice->sub)->get()->count();
        if ($check != 1) {
            $flag = 1;
        } else {
            StatsHelper::checkStatRow($userGice->sub);
        }

        // dd($userGcdice) ddddddddddddddddddddd              dwadwadawdwadddddddd;

        User::updateOrCreate(
            ['id' => $userGice->sub],
            [
                'name' => $userGice->name,
                'token' => $userGice->oi_tkn_id,
                'pri_grp' => $userGice->pri_grp,
                'last_logged_in' => now()
            ]
        );

        $user = User::where('id', $userGice->sub)->first();
        if (!$user->main_character_id) {
            $eveESIStatus = EveEsiStatus::where('endpoint', 'python-router')->where('route', '/universe/ids/')->where('tags', 'Universe')->first();
            if ($eveESIStatus) {
                $stats = $eveESIStatus->status;
                if ($stats == 'green') {
                    $mainID = Helper::userIdFromName($userGice->name);
                    $user->update(['main_character_id' => $mainID]);
                    StatsHelper::checkStatRow($userGice->sub);
                }
            }
        }

        $this->purgeRoles($user);
        if (isset($userGice->grp)) {
            $roles = $userGice->grp;
            if (is_array($roles)) {
                foreach ($roles as $role) {
                    $this->addRoles($user, $role);
                }
            } else {
                $this->addRoles(
                    $user,
                    $roles
                );
            }
            // return $roles;
        }

        Auth::login($user, true);

        // if ($flag == 1) {
        //     broadcast(new UserUpdate($flag))->toOthers();
        // }

        $url = session('url');
        if ($url == null) {
            $url = '/';
        }

        return redirect($url);
    }

    public function admin()
    {
        User::updateOrCreate(['id' => 5], ['name' => 'admin', 'token' => '456456456456456', 'pri_grp' => 5]);
        $user = User::where('id', 5)->first();
        Auth::login($user, true);

        return redirect('/home');
    }

    public function martyn()
    {
        User::updateOrCreate(['id' => 99999999], ['name' => 'martn', 'token' => '9999999999999999999999999', 'pri_grp' => 5]);
        $user = User::where('id', 99999999)->first();
        Auth::login($user, true);

        return redirect('/home');
    }

    public function evestuffUser()
    {
        $check = Auth::user();
        if ($check->can('super_admin')) {
            $user = User::where('id', 82686)->first();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token');

            return ['token' => $token->plainTextToken];
        }
    }

    public function getDscanLocation(Request $request)
    {
        $character_ids = $request->json()->all();
        $charIDs = ESITokens::whereIn('character_id', $character_ids)->get();
        foreach ($charIDs as $charID) {
            $data = evestuffLocationCheck($charID->character_id);
            if ($data['status'] == 'true') {
                return response()->json($data);
                break;
            }
        }
        return response()->json(['status' => 'false']);
    }



    public function scopeh()
    {
        User::updateOrCreate(['id' => 999999999], ['name' => 'Scopeh The Master', 'token' => '9999999999999999999999999', 'pri_grp' => 5]);
        $user = User::where('id', 999999999)->first();
        Auth::login($user, true);
        $user->assignRole('Super Admin');

        return redirect('/home');
        // return redirect('/');
    }

    public function monty()
    {
        User::updateOrCreate(['id' => 25107], ['name' => 'JohnMonty', 'token' => '82298be8-ba9e-48af-8123-184014c54bd9', 'pri_grp' => 9, 'character_id' => 717568371, 'main_character_id' => 717568371]);
        $user = User::where('id', 25107)->first();
        Auth::login($user, true);
        $user->assignRole('Super Admin');

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();

        return view('auth.login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function index()
    {
        return ['users' => User::select('id', 'name')->get()];
    }


    public function loginInfo()
    {
        $data = [
            "username" => Auth::user()->name,
            "user_id" => Auth::user()->id,
            "char" => Auth::user()->character_id,
        ];

        return ["data" => $data];
    }

    /*
    title -> gice_id -> site_id

    Director -> 8 -> 30
    skirmish fc -> 28 -> 36
    gsol -> 47 -> 37
    scout -> 195 -> 38
    ops -> 231 -> 39
    recon -> 184 -> 40
    recon-l -> 1094 -> 41
    coord -> 494 -> 35
    pathfinders -> 470 -> 29
    pathfinders-L -> 471 -> 42
    Genesis -> 1153 -> 43

    */

    public function purgeRoles($user)
    {
        $user->removeRole(30); // Director
        $user->removeRole(36); // Skirmish FC
        $user->removeRole(37); // gsol
        $user->removeRole(38); // scout
        $user->removeRole(39); // ops
        $user->removeRole(40); // recon
        $user->removeRole(41); // recon-1
        $user->removeRole(35); // coord
        $user->removeRole(29); // pathfinders
        $user->removeRole(42); // pathfinders-L
        $user->removeRole(43); // Genesis
        $user->removeRole(44); // Base
        $user->removeRole(47); // guardbbees
        $user->removeRole(48); // TC
        $user->removeRole(49); // FC
    }

    public function addRoles($user, $role_id)
    {
        if ($role_id == 8) {

            // function to assign Director
            $user->assignRole(30);
        }

        if ($role_id == 28) {

            // function to skirmish fc -> 28 -> 36
            $user->assignRole(36);
        }

        if ($role_id == 47) {

            // function to gsol
            $user->assignRole(37);
        }

        if ($role_id == 195) {

            // scout -> 195 -> 38
            $user->assignRole(38);
        }

        if ($role_id == 231) {

            // ops -> 231 -> 39
            $user->assignRole(39);
        }

        if ($role_id == 184) {

            // recon -> 184 -> 40
            $user->assignRole(40);
        }

        if ($role_id == 1094) {

            // recon-l -> 1094 -> 41
            $user->assignRole(41);
        }

        if ($role_id == 494) {

            // coord -> 494 -> 35
            $user->assignRole(35);
        }

        if ($role_id == 470) {

            // pathfinders -> 470 -> 29
            $user->assignRole(29);
        }

        if ($role_id == 471) {

            // pathfinders-L -> 471 -> 42
            $user->assignRole(42);
        }

        if ($role_id == 1153) {

            // pathfinders-L -> 471 -> 42
            $user->assignRole(43);
        }

        if ($role_id == 1297) {

            // pathfinders-L -> 471 -> 42
            $user->assignRole(47);
        }

        if ($role_id == 979) {

            // TC -> 979 -> 48
            $user->assignRole(48);
        }

        if ($role_id == 979) {

            // FC -> 731 -> 49
            $user->assignRole(49);
        }

        $user->assignRole(44);
    }
}
