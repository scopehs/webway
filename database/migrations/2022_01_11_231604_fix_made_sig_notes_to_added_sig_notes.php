<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;

class FixMadeSigNotesToAddedSigNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::where('id', 42)->update(['name' => 'Added Sig Notes']);
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
