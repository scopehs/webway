<?php

use App\Models\ActivityLog;
use App\Models\ActiviyCauserTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiviyCauserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activiy_causer_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // ActiviyCauserTypes::create(['id' => 1, 'name' => 'User']);
        // ActiviyCauserTypes::create(['id' => 2, 'name' => 'System']);
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('causer_type_id')->nullable();
        });
        // ActivityLog::where('causer_type', 'like', 'User')->update(['causer_type_id' => 1]);
        // ActivityLog::where('causer_type', 'like', 'System')->update(['causer_type_id' => 2]);
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('causer_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activiy_causer_types');
    }
}
