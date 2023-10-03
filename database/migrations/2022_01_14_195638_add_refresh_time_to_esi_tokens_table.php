<?php

use App\Models\EVE\ESITokens;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefreshTimeToEsiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esi_tokens', function (Blueprint $table) {
            $table->dateTime('token_refresh');
        });

        ESITokens::whereNotNull('id')->update(['token_refresh' => now()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esi_tokens', function (Blueprint $table) {
            //
        });
    }
}
