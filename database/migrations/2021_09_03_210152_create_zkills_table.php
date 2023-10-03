<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zkills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attackers_alliance_id')->nullable();
            $table->foreignId('attackers_character_id');
            $table->foreignId('attackers_corporation_id');
            $table->foreignId('attackers_ship_type_id');
            $table->timestamp('killmail_time');
            $table->foreignId('victim_alliance_id')->nullable();
            $table->foreignId('victim_character_id');
            $table->foreignId('victim_corporation_id');
            $table->foreignId('victim_ship_type_id');
            $table->foreignId('solar_system_id');
            $table->decimal('totalValue', 17, 2);

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
        Schema::dropIfExists('zkills');
    }
}
