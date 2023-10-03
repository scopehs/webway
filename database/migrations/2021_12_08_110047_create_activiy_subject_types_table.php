<?php

use App\Models\ActivityLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiviySubjectTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activiy_subject_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('subject_type_id')->nullable();
        });

        // ActivityLog::where('subject_type', 'like', 'Auth')->update(['subject_type_id' => 1]);
        // ActivityLog::where('subject_type', 'like', 'Char_tracking')->update(['subject_type_id' => 2]);
        // ActivityLog::where('subject_type', 'like', 'Character')->update(['subject_type_id' => 3]);
        // ActivityLog::where('subject_type', 'like', 'Connection')->update(['subject_type_id' => 4]);
        // ActivityLog::where('subject_type', 'like', 'JoveSystem')->update(['subject_type_id' => 5]);
        // ActivityLog::where('subject_type', 'like', 'jumpBridges')->update(['subject_type_id' => 6]);
        // ActivityLog::where('subject_type', 'like', 'Routing')->update(['subject_type_id' => 7]);
        // ActivityLog::where('subject_type', 'like', 'Sig')->update(['subject_type_id' => 8]);
        // ActivityLog::where('subject_type', 'like', 'Sigs')->update(['subject_type_id' => 8]);
        // ActivityLog::where('subject_type', 'like', 'url')->update(['subject_type_id' => 9]);
        // ActivityLog::where('subject_type', 'like', 'UserStats')->update(['subject_type_id' => 10]);
        // ActivityLog::where('subject_type', 'like', 'HotArea')->update(['subject_type_id' => 11]);
        // ActivityLog::where('subject_type', 'like', 'Eve')->update(['subject_type_id' => 12]);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('subject_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activiy_subject_types');
    }
}
