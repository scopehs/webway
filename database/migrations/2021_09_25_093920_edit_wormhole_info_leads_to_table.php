<?php

use App\Models\WormholeInfoLeadsTo;
use Illuminate\Database\Migrations\Migration;

class EditWormholeInfoLeadsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        WormholeInfoLeadsTo::where('id', 2)->update(['text' => 'C45', 'table_text' => 'C45']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        WormholeInfoLeadsTo::where('id', 2)->update(['text' => 'C5', 'table_text' => 'C5']);
    }
}
