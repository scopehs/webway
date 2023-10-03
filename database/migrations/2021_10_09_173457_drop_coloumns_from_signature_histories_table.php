<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColoumnsFromSignatureHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signature_histories', function (Blueprint $table) {
            $table->dropColumn('reported_mass');
            $table->dropColumn('reported_life');
            $table->dropColumn('life_length');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signature_histories', function (Blueprint $table) {
            //
        });
    }
}
