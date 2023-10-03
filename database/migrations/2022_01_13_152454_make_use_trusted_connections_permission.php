<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class MakeUseTrustedConnectionsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Directors" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Coord" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Skirmish FC" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Ops" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Recon" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "use_trusted_connections"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "use_trusted_connections"');
        Role::where('id', 45)->delete();
        Artisan::call('permission:create-role "Directors" web "make_reserved_connection"');
        Artisan::call('permission:create-role "Directors" web "use_reserved_connection"');
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
