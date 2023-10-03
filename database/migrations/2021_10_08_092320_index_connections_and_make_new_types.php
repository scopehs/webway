<?php

use App\Models\Connections\ConnectionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexConnectionsAndMakeNewTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->index('type');
        });

        ConnectionTypes::create(['id' => 10, 'name' => 'Titan Bridge']);
        ConnectionTypes::create(['id' => 11, 'name' => 'Blops Bridge']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });
    }
}
