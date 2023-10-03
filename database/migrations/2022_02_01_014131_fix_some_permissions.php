<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FixSomePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Recon Leader" web "view_hot_area"');
        Artisan::call('permission:create-role "GSOL" web "view_connection_notes"');
        Artisan::call('permission:create-role "Ops" web "use_reserved_connection"');
        Artisan::call('permission:create-role "GSOL" web "use_reserved_connection"');
        Artisan::call('permission:create-role "Recon" web "use_reserved_connection"');
        Artisan::call('permission:create-role "Genesis" web "delete_connections"');
        DB::table('role_has_permissions')->whereRaw('permission_id = 88')->delete();
        Artisan::call('permission:create-role "Pathfinder" web "connections_trusted"');
        Artisan::call('permission:create-role "Genesis" web "connections_trusted"');
        DB::table('role_has_permissions')->whereRaw('permission_id = 87')->delete();
        Artisan::call('permission:create-role "Pathfinder Leader" web "delete_sig_notes"');
        Artisan::call('permission:create-role "GSOL" web "make_reserved_connection"');
        Artisan::call('permission:create-role "Ops" web "make_reserved_connection"');
        DB::table('role_has_permissions')->whereRaw('permission_id = 89')->delete();
        Artisan::call('permission:create-role "GSOL" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Coord" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Directors" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Ops" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Recon" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "use_trusted_connections"');
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
