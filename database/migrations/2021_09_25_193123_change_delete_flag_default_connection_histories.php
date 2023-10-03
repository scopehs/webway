<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDeleteFlagDefaultConnectionHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_histories', function (Blueprint $table) {
            $table->dropColumn('delete_flag');
        });

        Schema::table('connection_histories', function (Blueprint $table) {
            $table->integer('delete_flag')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_histories', function (Blueprint $table) {
        });
    }
}
