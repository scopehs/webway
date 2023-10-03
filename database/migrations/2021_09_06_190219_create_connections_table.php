<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_sig_id')->nullable();
            $table->foreignId('target_sig_id')->nullable();
            $table->foreignId('source_system_id');
            $table->foreignId('target_system_id')->nullable();
            $table->foreignId('type'); // 1 Gate # 2 Wormhole # 3 Jump Bridge
            $table->integer('delete')->default(0);
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
        Schema::dropIfExists('connections');
    }
}
