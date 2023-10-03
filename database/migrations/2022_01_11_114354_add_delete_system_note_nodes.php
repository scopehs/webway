<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddDeleteSystemNoteNodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Directors web "delete_system_logs"');
        Artisan::call('permission:create-role Coord web "delete_system_logs"');
        Artisan::call('permission:create-role "Recon Leader" web "delete_system_logs"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "delete_system_logs"');
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
