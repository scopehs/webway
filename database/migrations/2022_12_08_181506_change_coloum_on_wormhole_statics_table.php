<?php

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
        Schema::table('wormhole_statics', function (Blueprint $table) {
            $table->foreignId('signature_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wormhole_statics', function (Blueprint $table) {
            $table->integer('signature_id')->nullable()->change();
        });
    }
};
