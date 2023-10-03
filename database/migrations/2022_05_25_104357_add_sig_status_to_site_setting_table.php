<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

class AddSigStatusToSiteSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SiteSetting::create(['name' => 'Sig List Status', 'state' => 1]);
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
