<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddWebWayUserToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        activity()->disableLogging();
        User::create([
            'id' => 2,
            'name' => 'WebWay',
            'token' => 84321,
            'main_character_id' => 90159429,

        ]);
        activity()->enableLogging();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('id', 2)->delete();
    }
}
