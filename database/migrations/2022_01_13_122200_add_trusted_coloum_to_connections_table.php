<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AddTrustedColoumToConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->boolean('trusted')->default(1);
        });

        Artisan::call('permission:create-role Directors web "connections_trusted"');
        Artisan::call('permission:create-role Coord web "connections_trusted"');
        Artisan::call('permission:create-role "Recon Leader" web "connections_trusted"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "connections_trusted"');
        Artisan::call('permission:create-role "GSOL" web "connections_trusted"');
        Artisan::call('permission:create-role "Ops" web "connections_trusted"');
        Artisan::call('permission:create-role "Recon" web "connections_trusted"');
        Artisan::call('permission:create-role "Pathfinder" web "connections_trusted"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connections', function (Blueprint $table) {
            //
        });
    }
}
