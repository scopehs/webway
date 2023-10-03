<?php

use App\Models\ActivityLog;
use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddPermissionsAndActiviyDescriptionTypesForConnectionNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ActiviyDescriptionTypes::create([
            'id' => 44,
            'name' => 'Added Connection Note',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 45,
            'name' => 'Delete Connection Note',
        ]);

        // ActivityLog::whereNull('subject_type')->whereIn('description_id', [2, 3])->update(['subject_type' => 'App\Models\Scanning\Signature']);
        // ActivityLog::whereNull('subject_type')->whereIn('description_id', [9])->update(['subject_type' => 'App\Models\Connections\Connections']);
        Artisan::call('permission:create-role Directors web "view_connection_notes"');
        Artisan::call('permission:create-role Coord web "view_connection_notes"');
        Artisan::call('permission:create-role Ops web "view_connection_notes"');
        Artisan::call('permission:create-role Recon web "view_connection_notes"');
        Artisan::call('permission:create-role "Recon Leader" web "view_connection_notes"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "view_connection_notes"');

        Artisan::call('permission:create-role Directors web "delete_connection_notes"');
        Artisan::call('permission:create-role "Coord" web "delete_connection_notes"');
        Artisan::call('permission:create-role "Recon Leader" web "delete_connection_notes"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "delete_connection_notes"');
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
