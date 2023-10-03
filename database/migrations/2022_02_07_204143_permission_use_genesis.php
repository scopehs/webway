<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class PermissionUseGenesis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Genesis" web "use_genesis"');
        Artisan::call('permission:create-role "Coord" web "use_genesis"');
        Artisan::call('permission:create-role "Ops" web "use_genesis"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "use_genesis"');
        Artisan::call('permission:create-role "Recon Leader" web "use_genesis"');
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
