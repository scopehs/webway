<?php

use App\Models\EVE\Characters;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainCharIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('main_character_id')->nullable();
        });

        $users = User::where('id', '>', 0)->get();
        foreach ($users as $user) {
            $chars = Characters::where('user_id', $user->id)->get();
            foreach ($chars as $char) {
                if ($char->name == $user->name) {
                    $user->update(['main_character_id' => $char->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
