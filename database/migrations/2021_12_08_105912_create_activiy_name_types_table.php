<?php

use App\Models\ActivityLog;
use App\Models\ActiviyNameTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiviyNameTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activiy_name_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        ActiviyNameTypes::create(['id' => 1, 'name' => 'default']);
        ActiviyNameTypes::create(['id' => 2, 'name' => 'url']);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('name_id')->nullable();
        });

        // ActivityLog::where('log_name', 'like', 'default')->update(['name_id' => 1]);
        // ActivityLog::where('log_name', 'like', 'url')->update(['name_id' => 2]);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('log_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activiy_name_types');
    }
}
