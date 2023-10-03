<?php

use App\Models\WormholeInfoLeadsTo;
use App\Models\WormholeInfoMass;
use App\Models\WormholeInfoShipSize;
use App\Models\WormholeInfoTimeTillDeath;
use Illuminate\Database\Migrations\Migration;

class ChangeTableTextForWormholeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        WormholeInfoShipSize::where('id', 1)->update(['table_text' => 'Very Large']);
        WormholeInfoShipSize::where('id', 2)->update(['table_text' => 'Larger']);
        WormholeInfoShipSize::where('id', 3)->update(['table_text' => 'Medium']);
        WormholeInfoShipSize::where('id', 4)->update(['table_text' => 'Frigate']);

        WormholeInfoLeadsTo::where('id', 1)->update(['table_text' => 'C1/C2/C3']);
        WormholeInfoLeadsTo::where('id', 2)->update(['table_text' => 'C5']);
        WormholeInfoLeadsTo::where('id', 3)->update(['table_text' => 'C6']);
        WormholeInfoLeadsTo::where('id', 4)->update(['table_text' => 'HS']);
        WormholeInfoLeadsTo::where('id', 5)->update(['table_text' => 'LS']);
        WormholeInfoLeadsTo::where('id', 6)->update(['table_text' => 'NS']);
        WormholeInfoLeadsTo::where('id', 7)->update(['table_text' => 'Pochven']);

        WormholeInfoMass::where('id', 1)->update(['table_text' => 'Stage 1']);
        WormholeInfoMass::where('id', 2)->update(['table_text' => 'Stage 2']);
        WormholeInfoMass::where('id', 3)->update(['table_text' => 'Stage 3']);

        WormholeInfoTimeTillDeath::where('id', 1)->update(['table_text' => 'Stage 1']);
        WormholeInfoTimeTillDeath::where('id', 2)->update(['table_text' => 'Stage 2']);
        WormholeInfoTimeTillDeath::where('id', 3)->update(['table_text' => 'Stage 3']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
