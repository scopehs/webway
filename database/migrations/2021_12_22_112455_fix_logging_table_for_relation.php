<?php

use App\Models\ActivityLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixLoggingTableForRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->renameColumn('subject_type_id', 'subject_type');
            $table->string('causer_type')->nullable();
        });

        // $logs = ActivityLog::all();

        // foreach ($logs as $log) {
        //     if ($log->causer_type_id == 1) {
        //         $log->update(['causer_type' => 'App\Models\User']);
        //     }
        // }

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('causer_type_id');
        });

        Schema::drop('activiy_causer_types');
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
