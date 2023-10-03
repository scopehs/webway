<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_types', function (Blueprint $table) {
            $table->id();
            $table->string('wormhole_type');
            $table->integer('life');
            $table->foreignId('leads_to');
            $table->bigInteger('mass');
            $table->bigInteger('jump');
            $table->integer('wandering');
            $table->bigInteger('regen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_types');
    }
}
