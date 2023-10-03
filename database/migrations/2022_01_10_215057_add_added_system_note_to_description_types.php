<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;

class AddAddedSystemNoteToDescriptionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::create([
            'id' => 40,
            'name' => 'Added System Note',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
