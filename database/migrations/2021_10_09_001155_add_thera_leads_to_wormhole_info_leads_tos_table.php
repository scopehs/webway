<?php

use App\Models\WormholeInfoLeadsTo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTheraLeadsToWormholeInfoLeadsTosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        WormholeInfoLeadsTo::create(['id' => 8, 'text' => 'Thera', 'table_text' => 'Thera']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wormhole_info_leads_tos', function (Blueprint $table) {
            //
        });
    }
}
