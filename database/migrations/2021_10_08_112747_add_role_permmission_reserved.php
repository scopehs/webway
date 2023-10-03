<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddRolePermmissionReserved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Coord web "make_reserved_connection | user_reserved_connection"');
        Artisan::call('permission:create-role Director web "make_reserved_connection | user_reserved_connection"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "make_reserved_connection | user_reserved_connection"');
        Artisan::call('permission:create-role "Recon Leader" web "make_reserved_connection | user_reserved_connection"');
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
