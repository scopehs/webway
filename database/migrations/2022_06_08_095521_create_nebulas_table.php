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
        Schema::create('nebulas', function (Blueprint $table) {
            $table->id();
            $table->integer('units');
            $table->string('name');
            $table->boolean('damage');
            $table->json('damage_type')->nullable();
            $table->boolean('npc');
            $table->boolean('dead_space');
            $table->boolean('ninja');
            $table->boolean('jedi');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'NebulaSeeder',
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
        Schema::dropIfExists('nebulas');
    }
};
