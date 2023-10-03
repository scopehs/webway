<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortest_paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('start_system_id');
            $table->foreignId('end_system_id');
            $table->uuid('link')->nullable();
            $table->integer('jumps')->nullable();
            $table->foreignId('added_by_id');
            $table->string('notes')->nullable();
            $table->integer('log_helper')->nullable();

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
        Schema::dropIfExists('shortest_paths');
    }
};
