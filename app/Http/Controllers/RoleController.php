<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('super_admin')) {
            return ['roles' => Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Wizard')->select('id', 'name')->orderBy('name', 'asc')->get()];
        }
    }

    public function removeRole(Request $request)
    {
        if (Auth::user()->can('super_admin')) {
            $user = User::find($request->userId);
            $user->removeRole($request->roleId);
            $flag = 1;
        }
    }

    public function addRole(Request $request)
    {
        if (Auth::user()->can('super_admin')) {
            $user = User::find($request->userId);
            $user->assignRole($request->roleId);
            $flag = 1;
        }
    }

    public function getAllUsersRoles()
    {
        if (Auth::user()->can('super_admin')) {
            return ['usersroles' => User::with('roles')->select('id', 'name')->get()];
        }
    }

    public function Wizard()
    {
        $user = User::find(25107);
        // $permissions = $user->getAllPermissions()->pluck("name");
        // // if($permissions == true){
        // //     echo "HAS ROLE";
        // // }else{
        // //     echo "NO ROLE";
        // // }
        // echo $permissions;
        // dd($permissions);
        $user->assignRole('Wizard');
        // $role = Role::findByName('Super Admin');
    }

    public function remove()
    {
        $role = User::where('name', 'Coord')->get();
        $permission = Permission::where('name', 'edit_all_users')->get();
        $role->revokePermissionTo($permission);
        // $permissions = $user->getAllPermissions()->pluck("name");
        // // if($permissions == true){
        // //     echo "HAS ROLE";
        // // }else{
        // //     echo "NO ROLE";
        // // }
        // echo $permissions;
        // dd($permissions);
        // $user->assignRole('Wizard');
        // $role = Role::findByName('Super Admin');
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
     * Display the specified resource.
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
