<?php

use App\Models\SystemType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_full');
        });

        SystemType::create(['id' => 1, 'name' => 'C1', 'name_full' => 'Class 1']);
        SystemType::create(['id' => 2, 'name' => 'C2', 'name_full' => 'Class 2']);
        SystemType::create(['id' => 3, 'name' => 'C3', 'name_full' => 'Class 3']);
        SystemType::create(['id' => 4, 'name' => 'C4', 'name_full' => 'Class 4']);
        SystemType::create(['id' => 5, 'name' => 'C5', 'name_full' => 'Class 5']);
        SystemType::create(['id' => 6, 'name' => 'C6', 'name_full' => 'Class 6']);
        SystemType::create(['id' => 7, 'name' => 'HS', 'name_full' => 'Highsec']);
        SystemType::create(['id' => 8, 'name' => 'LS', 'name_full' => 'Lowsec']);
        SystemType::create(['id' => 9, 'name' => 'NS', 'name_full' => 'Nullsec']);
        SystemType::create(['id' => 12, 'name' => 'Thera', 'name_full' => 'Thera']);
        SystemType::create(['id' => 13, 'name' => 'C13', 'name_full' => 'Class 13 - Shattered Frigate']);
        SystemType::create(['id' => 14, 'name' => 'C14', 'name_full' => 'Class 14 - Sentinel Drifter']);
        SystemType::create(['id' => 15, 'name' => 'C15', 'name_full' => 'Class 15 - Barbican Drifter']);
        SystemType::create(['id' => 16, 'name' => 'C16', 'name_full' => 'Class 16 - Vidette Drifter']);
        SystemType::create(['id' => 17, 'name' => 'C17', 'name_full' => 'Class 17 - Conflux Drifter']);
        SystemType::create(['id' => 18, 'name' => 'C18', 'name_full' => 'Class 18 - Redoubt Drifter']);
        SystemType::create(['id' => 25, 'name' => 'Trig', 'name_full' => 'Triglavian']);
    }

    /**
     * Reverse tsssshe migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_types');
    }
}
