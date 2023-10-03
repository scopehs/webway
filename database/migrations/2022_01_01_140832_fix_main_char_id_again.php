<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Http;

class FixMainCharIdAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::where('id', '>', 0)->get();
        foreach ($users as $user) {
            $url = 'https://esi.evetech.net/latest/search/?categories=character&datasource=tranquility&language=en&search='.$user->name.'&strict=true';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->get($url);

            $char = $response->collect();
            $char = $char->first();
            if ($char) {
                $id = $char[0];
                $user->update(['main_character_id' => $id]);
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
        //
    }
}
