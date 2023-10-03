<?php

use App\Models\EVE\Characters;
use App\Models\EVE\ESITokens;
use Illuminate\Database\Migrations\Migration;

class WebwayUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Characters::truncate();
        ESITokens::truncate();
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
