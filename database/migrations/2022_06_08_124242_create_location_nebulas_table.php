<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_nebulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nebula_id');
            $table->foreignId('location_id');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'LocationNebulaSeeder',
            '--force' => true, // <--- add this line
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_nebulas');
    }
};
