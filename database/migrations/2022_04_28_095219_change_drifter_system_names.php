<?php

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Migrations\Migration;

class ChangeDrifterSystemNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SolarSystem::where('system_id', 31000003)->update(['name' => 'Vidette - J164710']);
        SolarSystem::where('system_id', 31000006)->update(['name' => 'Redoubt - J174618']);
        SolarSystem::where('system_id', 31000001)->update(['name' => 'Sentinel - J055520']);
        SolarSystem::where('system_id', 31000002)->update(['name' => 'Barbican - J110145']);
        SolarSystem::where('system_id', 31000004)->update(['name' => 'Conflux - J200727']);
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
