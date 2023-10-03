<?php

use App\Models\WormholeInfoTimeTillDeath;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeInfoTimeTillDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_info_time_till_deaths', function (Blueprint $table) {
            $table->id();
            $table->string('text');
        });

        WormholeInfoTimeTillDeath::create(['id' => 1, 'text' => 'more than 24 hours']);
        WormholeInfoTimeTillDeath::create(['id' => 2, 'text' => 'between 4 and 24 hours']);
        WormholeInfoTimeTillDeath::create(['id' => 3, 'text' => 'less than 10%']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_info_time_till_deaths');
    }
}
