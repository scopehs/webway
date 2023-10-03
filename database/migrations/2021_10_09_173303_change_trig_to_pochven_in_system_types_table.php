<?php

use App\Models\SystemType;
use Illuminate\Database\Migrations\Migration;

class ChangeTrigToPochvenInSystemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SystemType::where('id', 25)->update(['name_full' => 'Pochven', 'name' => 'Poch']);
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
