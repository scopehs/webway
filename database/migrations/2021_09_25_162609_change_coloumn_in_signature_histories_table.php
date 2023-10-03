<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColoumnInSignatureHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signature_histories', function (Blueprint $table) {
            $table->renameColumn('delete_flag', 'delete');
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
            $table->renameColumn('delete', 'delete_flag');
        });
    }
}
