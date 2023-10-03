<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('char_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id');
            $table->foreignId('current_system_id');
            $table->foreignId('last_system_id');
            $table->foreignId('ship_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('char_trackings');
    }
}
