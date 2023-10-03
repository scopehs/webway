<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWormholeTypeToWormwholeTypeIdInWormholeStaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wormhole_statics', function (Blueprint $table) {
            // $table->renameColumn('wormhole_type', 'wormhole_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wormhole_statics', function (Blueprint $table) {
            //
        });
    }
}
