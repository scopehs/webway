<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FixAllConnectionRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('role_has_permissions')->whereRaw('permission_id = 93')->delete();
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_all_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "view_all_connections"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
