<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveReservedLogging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::create(['id' => 39, 'name' => 'Remove Reserved from Connection']);
        Schema::table('activity_log_snap_shots', function (Blueprint $table) {
            $table->smallInteger('39')->nullable()->default(null);
        });
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
