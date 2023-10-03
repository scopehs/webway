<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColounmsInSavedRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_routes', function (Blueprint $table) {
            $table->renameColumn('avoid_system_types', 'settings');
            $table->dropColumn('avoid_mass');
            $table->dropColumn('avoid_size');
            $table->dropColumn('avoid_life');
            $table->dropColumn('titans');
            $table->dropColumn('blops');
            $table->uuid('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_routes', function (Blueprint $table) {
            //
        });
    }
}
