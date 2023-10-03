<?php

use App\Models\Connections\ConnectionTypes;
use Illuminate\Database\Migrations\Migration;

class AddRowToConnectionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConnectionTypes::create([
            'id' => 4,
            'name' => 'Thera',

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ConnectionTypes::where('id', 4)->delete();
    }
}
