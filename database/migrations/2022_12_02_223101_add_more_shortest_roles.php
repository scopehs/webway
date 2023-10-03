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
        Artisan::call('permission:create-role Recon web "view_shortest | edit_shortest"');
        Artisan::call('permission:create-role "Recon Leader" web "view_shortest | edit_shortest"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_shortest | edit_shortest"');
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
