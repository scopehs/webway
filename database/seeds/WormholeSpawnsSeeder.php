<?php

namespace Database\Seeders;

use App\Http\Controllers\Helpers\Wormholes;
use App\Models\Wormholes\WormholeSpawns;
use Illuminate\Database\Seeder;

class WormholeSpawnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This seeder will populate the different wormhole types.

        $file = 'database/json/wormhole_data/wormhole_spawns.json';

        // Check the file exists

        if (file_exists($file)) {

            // File found, import.

            $this->command->info('Found Wormhole Spawns...');
            $this->command->info('Importing....');

            $contents = file_get_contents($file);
            $wormhole_spawns = json_decode($contents, true);

            $this->command->getOutput()->progressStart(count(json_decode($contents, true)));

            // Before Importing, TRUNCATE Existing DB.

            WormholeSpawns::truncate();

            foreach ($wormhole_spawns as $wormhole_type => $wormhole) {
                $x = 0;

                // Get the wormhole type id
                $wormhole_type_id = Wormholes::type($wormhole_type);

                // Get the wormhole type id
                while ($x < count($wormhole)) {
                    //$this->command->info($wormhole_type . ' ' . $wormhole_type_id . ' ' . $wormhole[$x]);
                    $this->addSpawn($wormhole_type_id, $wormhole[$x]);
                    $x++;
                }

                $this->command->getOutput()->progressAdvance();
            }

            $this->command->getOutput()->progressFinish();
        } else {
            $this->command->info('File not found.');
        }
    }

    public function addSpawn($wormhole_type, $system_type)
    {
        $insert = new WormholeSpawns();
        $insert->wormhole_type = $wormhole_type;
        $insert->system_type = $system_type;
        $insert->save();
    }
}
