<?php

use App\Models\LocationNebula;
use App\Models\Nebula;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $nebs = Nebula::all();
        foreach ($nebs as $n) {
            $location = LocationNebula::where('nebula_id', $n->id)->first();
            if ($location) {
                $n->location_id = $location->location_id;
                $n->location_type = 'region';
                $n->save();
            }
        }
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
};
