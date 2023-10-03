<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddRolesAndTypesForSigNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Directors web "delete_sig_notes"');
        Artisan::call('permission:create-role Coord web "delete_sig_notes"');
        Artisan::call('permission:create-role "Recon Leader" web "delete_sig_notes"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "delete_sig_notes"');

        ActiviyDescriptionTypes::create([
            'id' => 42,
            'name' => 'Made Sig Notes',
        ]);

        ActiviyDescriptionTypes::create([
            'id' => 43,
            'name' => 'Deleted Sig Notes',
        ]);
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
