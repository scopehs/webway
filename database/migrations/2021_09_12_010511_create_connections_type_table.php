<?php

use App\Models\Connections\ConnectionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        ConnectionTypes::create(['id' => 1, 'name' => 'Gate']);
        ConnectionTypes::create(['id' => 2, 'name' => 'Wormhole']);
        ConnectionTypes::create(['id' => 3, 'name' => 'Jump Bridge']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connection_types');
    }
}
