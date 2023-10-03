<?php

use App\Models\Connections\ConnectionTypes;
use Illuminate\Database\Migrations\Migration;

class ChangeConnectionTypeNameForTitanAndBlopsInConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConnectionTypes::where('name', 'Titan Bridge')->update(['name' => 'Bridge']);
        ConnectionTypes::where('name', 'Blops Bridge')->update(['name' => 'Bridge']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
