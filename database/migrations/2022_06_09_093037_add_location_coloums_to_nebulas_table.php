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
        Schema::table('nebulas', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable();
            $table->string('location_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nebulas', function (Blueprint $table) {
            $table->dropColumn('location_id');
            $table->dropColumn('location_type');
        });
    }
};
