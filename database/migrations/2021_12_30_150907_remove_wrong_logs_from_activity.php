<?php

use App\Models\ActivityLog;
use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;

class RemoveWrongLogsFromActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ActivityLog::where('subject_type', 'App\Models\Connections')->update(['subject_type' => 'App\Models\Connections\Connections']);
        // ActivityLog::where('subject_type', 'App\Models\Signature')->update(['subject_type' => 'App\Models\Scanning\Signature']);
        // ActivityLog::where('subject_type', 1)->delete();

        // ActivityLog::whereIn('description_id', [6, 8, 10, 15, 19, 23, 25, 38])->delete();
        ActiviyDescriptionTypes::whereIn('id', [6, 8, 10, 15, 19, 23, 25, 38])->delete();
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
