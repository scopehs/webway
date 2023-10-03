<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    /*
    [
        "FRX-736",
        "Cosmic Signature",
        "",
        "",
        "0.0%",
        "30.70 AU"
    ]

    [
        "FBH-845",
        "Cosmic Anomaly",
        "Combat Site",
        "Blood Raider Forlorn Den",
        "100.0%",
        "38.41 AU"
    ]

    # SID ID                - signature_id (string)
    # TYPE                  - type (string) (index) (nullable)
    # GROUP                 - group (string)
    # NAME                  - name (string) (nullable)
    # SIGNAL STR
    # DISTANCE WHEN SCANNED
    */

    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('signature_id');
            $table->integer('system_id')->index();
            $table->foreignId('type')->nullable(); // Ignore Cosmic If, Wormhole, Should be K162/A009/ Wormhole Type.
            $table->string('group'); // Wormhole / Gas / Data / Relic / Combat
            $table->string('name')->nullable();
            $table->foreignId('leads_to')->nullable();
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
        Schema::dropIfExists('signatures');
    }
}
