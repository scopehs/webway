<?php

namespace Database\Seeders;

use App\Models\SDE\mapDenormalize;
use App\Models\Wormholes\WormholeSystemEffects;
use Illuminate\Database\Seeder;

class WormholeSystemEffectsSeeder extends Seeder
{
    // DEFINE TYPES OF SYSTEMS (STARS)

    public const MAGNETAR = 30574;

    public const BLACK_HOLE = 30575;

    public const RED_GIANT = 30576;

    public const PULSAR = 30577;

    public const WOLF_RAYET = 30669;

    public const CATA_VARI = 30670;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    // PURGE THE TABLE BEFORE RESEEDING

        WormholeSystemEffects::truncate();

        // Query Mapdenormalize for group 995, for all the system ID's

        $systems_with_effects = mapDenormalize::where('groupID', 995)->get();

        foreach ($systems_with_effects as $system) {
            $insert = new WormholeSystemEffects();
            $insert->system_id = $system->solarSystemID;
            $insert->system_type = $system->typeID;
            $insert->save();
        }
    }
}
