<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatureHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signature_histories', function (Blueprint $table) {
            $table->id();
            $table->string('signature_id')->nullable();
            $table->integer('system_id')->index();
            $table->foreignId('type')->nullable(); // Ignore Cosmic If, Wormhole, Should be K162/A009/ Wormhole Type.
            $table->string('name')->nullable();
            $table->foreignId('leads_to')->nullable();
            $table->foreignId('connection_id')->nullable();
            $table->string('reported_mass')->nullable(); // Stable / Critical
            $table->string('reported_life')->nullable(); // Stable / Critical
            $table->float('signal_strength', 5, 2);
            $table->string('bookmark_syntax');
            $table->datetime('life_time'); // may use timestamps created_at
            $table->datetime('life_left');
            $table->integer('life_length'); // look up wormhole sig/type details to pull in this info and +time to created_at to automatically migrate to sig_history table to avoid birthday problem.
            $table->boolean('delete')->default(0);
            $table->integer('created_by_id')->index();
            $table->string('created_by_name');
            $table->integer('modified_by_id')->index()->nullable();
            $table->string('modified_by_name')->nullable();
            $table->foreignId('wormhole_info_ship_size_id')->nullable();
            $table->foreignId('wormhole_info_leads_to_id')->nullable();
            $table->foreignId('wormhole_info_mass_id')->nullable();
            $table->foreignId('wormhole_info_time_till_death_id')->nullable();
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
        Schema::dropIfExists('signature_histories');
    }
}
