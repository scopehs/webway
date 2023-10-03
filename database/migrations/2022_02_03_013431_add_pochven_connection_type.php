<?php

use App\Models\Connections\ConnectionTypes;
use Illuminate\Database\Migrations\Migration;

class AddPochvenConnectionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConnectionTypes::create(['id' => 5, 'name' => 'Pochven']);
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
