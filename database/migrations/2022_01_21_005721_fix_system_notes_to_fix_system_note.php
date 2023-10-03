<?php

use App\Models\ActivityLog;
use Illuminate\Database\Migrations\Migration;

class FixSystemNotesToFixSystemNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ActivityLog::whereIn('description_id', [40, 41])->update(['subject_type' => 'App\Models\SystemNote']);
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
