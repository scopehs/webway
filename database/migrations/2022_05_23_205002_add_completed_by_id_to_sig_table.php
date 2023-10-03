<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedByIdToSigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->foreignId('completed_by_id')->nullable();
            $table->string('completed_by_name')->nullable();
        });

        Schema::table('signature_histories', function (Blueprint $table) {
            $table->foreignId('completed_by_id')->nullable();
            $table->string('completed_by_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            //
        });

        Schema::table('signature_histories', function (Blueprint $table) {
            //
        });
    }
}
