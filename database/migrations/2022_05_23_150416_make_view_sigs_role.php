<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class MakeViewSigsRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Coord" web "view_sigs"');
        Artisan::call('permission:create-role "Directors" web "view_sigs"');
        Artisan::call('permission:create-role "Recon" web "view_sigs"');
        Artisan::call('permission:create-role "Recon Leader" web "view_sigs"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_sigs"');
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
