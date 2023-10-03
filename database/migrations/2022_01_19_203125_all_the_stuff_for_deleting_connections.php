<?php

use App\Models\ActiviyDescriptionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AllTheStuffForDeletingConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role Directors web "delete_connections"');
        Artisan::call('permission:create-role "Coord" web "delete_connections"');
        Artisan::call('permission:create-role "Recon Leader" web "delete_connections"');
        Artisan::call('permission:create-role "Pathfinder Leader" web "delete_connections"');
        Artisan::call('permission:create-role Recon web "delete_connections"');
        Artisan::call('permission:create-role Ops web "delete_connections"');
        ActiviyDescriptionTypes::create([
            'id' => 46,
            'name' => 'Archived Connection',
        ]);
        ActiviyDescriptionTypes::create([
            'id' => 47,
            'name' => 'Reported Connection as Gone',
        ]);

        Schema::table('connections', function (Blueprint $table) {
            $table->tinyInteger('report_count')->default(0);
        });
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
