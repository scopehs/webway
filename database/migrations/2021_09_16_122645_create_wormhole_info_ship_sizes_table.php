<?php

use App\Models\WormholeInfoShipSize;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeInfoShipSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_info_ship_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('text');
        });

        WormholeInfoShipSize::create(['id' => 1, 'text' => 'Carrier']);
        WormholeInfoShipSize::create(['id' => 2, 'text' => 'Battleship']);
        WormholeInfoShipSize::create(['id' => 3, 'text' => 'Battlecruiser']);
        WormholeInfoShipSize::create(['id' => 4, 'text' => 'Destroyer']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_info_ship_sizes');
    }
}
