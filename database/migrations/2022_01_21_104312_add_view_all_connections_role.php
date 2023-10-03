<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddViewAllConnectionsRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Directors web "view_all_connections"');
        Artisan::call('permission:create-role "Coord" web "view_all_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "view_all_connections"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_all_connections"');
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
