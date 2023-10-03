<?php

use App\Models\ActivityLog;
use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiviyDescriptionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activiy_description_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        ActiviyDescriptionTypes::create([
            'id' => 1,
            'name' => 'Added Drifter Hole',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 2,
            'name' => 'Added Leads to Sig',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 3,
            'name' => 'Added Leads to System',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 4,
            'name' => 'Added Sig',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 5,
            'name' => 'Added Sig ID',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 6,
            'name' => 'AllTheStats',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 7,
            'name' => 'Characters',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 8,
            'name' => 'Create',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 9,
            'name' => 'Created Connection',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 10,
            'name' => 'Get',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 11,
            'name' => 'Hotarea',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 12,
            'name' => 'Logged Out',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 13,
            'name' => 'Logged In',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 14,
            'name' => 'Mapping',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 15,
            'name' => 'Request',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 16,
            'name' => 'Requested Route',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 17,
            'name' => 'Route',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 18,
            'name' => 'Soft Deleted Sig',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 19,
            'name' => 'Update',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 20,
            'name' => 'Update Sig',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 21,
            'name' => 'Updated Jump Bridges',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 22,
            'name' => 'Updated Jove System Info',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 23,
            'name' => 'Updated Sig',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 24,
            'name' => 'Updated Sig Info',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 25,
            'name' => 'Updated Tracking Status',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 26,
            'name' => 'Whaling',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 27,
            'name' => 'Added ESI',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 28,
            'name' => 'Reserved Connection',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 29,
            'name' => 'Added Hot Area',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 30,
            'name' => 'Removed Hot Area',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 31,
            'name' => 'Cleared Whale Sig',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 32,
            'name' => 'Deleted Whale Connection',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 33,
            'name' => 'Added Whale Sig',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 34,
            'name' => 'Added Whale Connection',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 35,
            'name' => 'Reported Sig Gone',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 36,
            'name' => 'Archived Connection',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 37,
            'name' => 'Archived Sig',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 38,
            'name' => 'Set Waypoint',
        ]);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('description_id')->nullable();
        });

        // ActivityLog::where('description', 'like', 'Added Drifter Hole')->update(['description_id' => 1]);
        // ActivityLog::where('description', 'like', 'Added Leads to Sig')->update(['description_id' => 2]);
        // ActivityLog::where('description', 'like', 'Added Leads to System')->update(['description_id' => 3]);
        // ActivityLog::where('description', 'like', 'Added Sig')->update(['description_id' => 4]);
        // ActivityLog::where('description', 'like', 'Added Sig ID')->update(['description_id' => 5]);
        // ActivityLog::where('description', 'like', 'allthestats')->update(['description_id' => 6]);
        // ActivityLog::where('description', 'like', 'characters')->update(['description_id' => 7]);
        // ActivityLog::where('description', 'like', 'create')->update(['description_id' => 8]);
        // ActivityLog::where('description', 'like', 'Created Connection')->update(['description_id' => 9]);
        // ActivityLog::where('description', 'like', 'get')->update(['description_id' => 10]);
        // ActivityLog::where('description', 'like', 'hotarea')->update(['description_id' => 11]);
        // ActivityLog::where('description', 'like', 'Logged Out')->update(['description_id' => 12]);
        // ActivityLog::where('description', 'like', 'login')->update(['description_id' => 13]);
        // ActivityLog::where('description', 'like', 'mapping')->update(['description_id' => 14]);
        // ActivityLog::where('description', 'like', 'request')->update(['description_id' => 15]);
        // ActivityLog::where('description', 'like', 'Requested Route')->update(['description_id' => 16]);
        // ActivityLog::where('description', 'like', 'route')->update(['description_id' => 17]);
        // ActivityLog::where('description', 'like', 'Soft Deleted Sig')->update(['description_id' => 18]);
        // ActivityLog::where('description', 'like', 'update')->update(['description_id' => 19]);
        // ActivityLog::where('description', 'like', 'Update Sig')->update(['description_id' => 20]);
        // ActivityLog::where('description', 'like', 'updated')->update(['description_id' => 21]);
        // ActivityLog::where('description', 'like', 'Updated Jove System Info')->update(['description_id' => 22]);
        // ActivityLog::where('description', 'like', 'Updated Sig')->update(['description_id' => 23]);
        // ActivityLog::where('description', 'like', 'Updated Sig Info')->update(['description_id' => 24]);
        // ActivityLog::where('description', 'like', 'updated tracking status')->update(['description_id' => 25]);
        // ActivityLog::where('description', 'like', 'whaling')->update(['description_id' => 26]);
        // ActivityLog::where('description', 'like', 'Added ESI')->update(['description_id' => 27]);
        // ActivityLog::where('description', 'like', 'Reserved Connection')->update(['description_id' => 28]);
        // ActivityLog::where('description', 'like', 'Added Hot Area')->update(['description_id' => 29]);
        // ActivityLog::where('description', 'like', 'Removed Hot Area')->update(['description_id' => 30]);
        // ActivityLog::where('description', 'like', 'Cleared Whale Sig')->update(['description_id' => 31]);
        // ActivityLog::where('description', 'like', 'Deleted Whale Connection')->update(['description_id' => 32]);
        // ActivityLog::where('description', 'like', 'Added Whale Sig')->update(['description_id' => 33]);
        // ActivityLog::where('description', 'like', 'Added Whale Connection')->update(['description_id' => 34]);
        // ActivityLog::where('description', 'like', 'Report Sig Gone')->update(['description_id' => 35]);
        // ActivityLog::where('description', 'like', 'Archived Connection')->update(['description_id' => 36]);
        // ActivityLog::where('description', 'like', 'Archived Sig')->update(['description_id' => 37]);
        // ActivityLog::where('description', 'like', 'Set Waypoint')->update(['description_id' => 38]);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activiy_description_types');
    }
}
