<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::create([
            'id' => 50,
            'name' => 'Reserved Gas Site',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 51,
            'name' => 'Un-Reserved Gas Site',
        ]);

        Schema::table('activity_log_snap_shots', function (Blueprint $table) {
            $table->smallInteger('50')->nullable();
            $table->smallInteger('51')->nullable();
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
};
