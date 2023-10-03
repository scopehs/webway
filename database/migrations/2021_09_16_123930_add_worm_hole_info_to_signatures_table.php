<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWormHoleInfoToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->foreignId('wormhole_info_ship_size_id')->nullable();
            $table->foreignId('wormhole_info_leads_to_id')->nullable();
            $table->foreignId('wormhole_info_mass_id')->nullable();
            $table->foreignId('wormhole_info_time_till_death_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            //
        });
    }
}
