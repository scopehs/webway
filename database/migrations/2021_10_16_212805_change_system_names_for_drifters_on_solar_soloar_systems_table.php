<?php

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Migrations\Migration;

class ChangeSystemNamesForDriftersOnSolarSoloarSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SolarSystem::where('system_id', 31000003)->update(['name' => 'Vidette']);
        SolarSystem::where('system_id', 31000006)->update(['name' => 'Redoubt']);
        SolarSystem::where('system_id', 31000001)->update(['name' => 'Sentinel']);
        SolarSystem::where('system_id', 31000002)->update(['name' => 'Barbican']);
        SolarSystem::where('system_id', 31000004)->update(['name' => 'Conflux']);
    }
}
