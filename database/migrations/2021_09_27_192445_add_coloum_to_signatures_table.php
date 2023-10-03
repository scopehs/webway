<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->string('name_id')->after('signature_id');
        });

        Schema::table('signature_histories', function (Blueprint $table) {
            $table->string('name_id')->after('signature_id');
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
            $table->dropColumn('name_id');
        });

        Schema::table('signature_histories', function (Blueprint $table) {
            $table->dropColumn('name_id');
        });
    }
}
