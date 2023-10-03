<?php

use App\Models\WormholeInfoLeadsTo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeInfoLeadsTosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wormhole_info_leads_tos', function (Blueprint $table) {
            $table->id();
            $table->string('text');
        });

        WormholeInfoLeadsTo::create(['id' => 1, 'text' => 'C1/C2/C3']);
        WormholeInfoLeadsTo::create(['id' => 2, 'text' => 'C5']);
        WormholeInfoLeadsTo::create(['id' => 3, 'text' => 'C6']);
        WormholeInfoLeadsTo::create(['id' => 4, 'text' => 'HS']);
        WormholeInfoLeadsTo::create(['id' => 5, 'text' => 'LS']);
        WormholeInfoLeadsTo::create(['id' => 6, 'text' => 'NS']);
        WormholeInfoLeadsTo::create(['id' => 7, 'text' => 'Pochven']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wormhole_info_leads_tos');
    }
}
