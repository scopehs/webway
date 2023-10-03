<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Coord web "view_broken_connections"');
        Artisan::call('permission:create-role Directors web "view_broken_connections"');
        Artisan::call('permission:create-role Pathfinder web "view_broken_connections"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_broken_connections"');
        Artisan::call('permission:create-role Recon web "view_broken_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "view_broken_connections"');
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
};
