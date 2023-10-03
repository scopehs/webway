<?php

use App\Models\SystemSystemType;
use Illuminate\Database\Migrations\Migration;

class FixSystemSystemTypesForDifterHoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SystemSystemType::where('system_id', 31000001)->where('system_type_id', 1)->delete();
        SystemSystemType::where('system_id', 31000002)->where('system_type_id', 1)->delete();
        SystemSystemType::where('system_id', 31000003)->where('system_type_id', 1)->delete();
        SystemSystemType::where('system_id', 31000004)->where('system_type_id', 1)->delete();
        SystemSystemType::where('system_id', 31000006)->where('system_type_id', 1)->delete();
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
