<?php

use App\Models\EffectList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEffectListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('effect_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        EffectList::create(['id' => 30575, 'name' => 'Black Hole']);
        EffectList::create(['id' => 30574, 'name' => 'Magnetar']);
        EffectList::create(['id' => 30576, 'name' => 'Red Giant']);
        EffectList::create(['id' => 30577, 'name' => 'Pulsar']);
        EffectList::create(['id' => 30669, 'name' => 'Wolf Rayet']);
        EffectList::create(['id' => 30670, 'name' => 'Cataclysmic Variable']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('effect_lists');
    }
}
