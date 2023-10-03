<?php

use App\Models\WormholeInfoMass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeInfoMassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_info_masses', function (Blueprint $table) {
            $table->id();
            $table->string('text');
        });

        WormholeInfoMass::create(['id' => 1, 'text' => 'over 50%']);
        WormholeInfoMass::create(['id' => 2, 'text' => 'between 50% and 10%']);
        WormholeInfoMass::create(['id' => 3, 'text' => 'less than 4 hours']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_info_masses');
    }
}
