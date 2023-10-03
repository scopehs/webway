<?php

use App\Models\WormholeInfoLeadsTo;
use App\Models\WormholeInfoMass;
use App\Models\WormholeInfoShipSize;
use App\Models\WormholeInfoTimeTillDeath;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableTextColonmsToInfoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wormhole_info_ship_sizes', function (Blueprint $table) {
            $table->string('table_text');
        });

        WormholeInfoShipSize::where('id', 1)->update(['table_text' => 'X-Large']);
        WormholeInfoShipSize::where('id', 2)->update(['table_text' => 'Large']);
        WormholeInfoShipSize::where('id', 3)->update(['table_text' => 'Medium']);
        WormholeInfoShipSize::where('id', 4)->update(['table_text' => 'Small']);

        Schema::table('wormhole_info_leads_tos', function (Blueprint $table) {
            $table->string('table_text');
        });

        WormholeInfoLeadsTo::where('id', 1)->update(['table_text' => 'C1/C2/C3']);
        WormholeInfoLeadsTo::where('id', 2)->update(['table_text' => 'C5']);
        WormholeInfoLeadsTo::where('id', 3)->update(['table_text' => 'C6']);
        WormholeInfoLeadsTo::where('id', 4)->update(['table_text' => 'HS']);
        WormholeInfoLeadsTo::where('id', 5)->update(['table_text' => 'LS']);
        WormholeInfoLeadsTo::where('id', 6)->update(['table_text' => 'NS']);
        WormholeInfoLeadsTo::where('id', 7)->update(['table_text' => 'Pochven']);

        Schema::table('wormhole_info_masses', function (Blueprint $table) {
            $table->string('table_text');
        });

        WormholeInfoMass::where('id', 1)->update(['table_text' => '50% +']);
        WormholeInfoMass::where('id', 2)->update(['table_text' => '10% - 50%']);
        WormholeInfoMass::where('id', 3)->update(['table_text' => '0% - 10%']);

        Schema::table('wormhole_info_time_till_deaths', function (Blueprint $table) {
            $table->string('table_text');
        });

        WormholeInfoTimeTillDeath::where('id', 1)->update(['table_text' => '24 +']);
        WormholeInfoTimeTillDeath::where('id', 2)->update(['table_text' => '4 - 24']);
        WormholeInfoTimeTillDeath::where('id', 3)->update(['table_text' => '0 - 4']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_tables', function (Blueprint $table) {
            //
        });
    }
}
