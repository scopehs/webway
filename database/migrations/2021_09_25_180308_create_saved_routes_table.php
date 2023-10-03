<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->foreignId('start_systemm_id');
            $table->foreignId('end_system_id');
            $table->boolean('saved')->default(0);
            $table->json('route');
            $table->json('avoid_system_types')->nullable();
            $table->json('avoid_mass')->nullable();
            $table->json('avoid_size')->nullable();
            $table->json('avoid_life')->nullable();
            $table->json('titans')->nullable();
            $table->json('blops')->nullable();
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
        Schema::dropIfExists('saved_routes');
    }
}
