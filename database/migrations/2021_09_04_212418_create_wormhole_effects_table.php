<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeEffectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->index();
            $table->string('hole_type')->index();
            $table->string('effect')->index();
            $table->string('icon');
            $table->decimal('C1', 5, 2);
            $table->decimal('C2', 5, 2);
            $table->decimal('C3', 5, 2);
            $table->decimal('C4', 5, 2);
            $table->decimal('C5', 5, 2);
            $table->decimal('C6', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_effects');
    }
}
