<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoggingStuffForReservingSigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::create([
            'id' => 48,
            'name' => 'Reserved Sig',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 49,
            'name' => 'Un-Reserved Sig',
        ]);

        Schema::table('activity_log_snap_shots', function (Blueprint $table) {
            $table->smallInteger('40')->nullable();
            $table->smallInteger('41')->nullable();
            $table->smallInteger('42')->nullable();
            $table->smallInteger('43')->nullable();
            $table->smallInteger('44')->nullable();
            $table->smallInteger('45')->nullable();
            $table->smallInteger('46')->nullable();
            $table->smallInteger('47')->nullable();
            $table->smallInteger('48')->nullable();
            $table->smallInteger('49')->nullable();
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
