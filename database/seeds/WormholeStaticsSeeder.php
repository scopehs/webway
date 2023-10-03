<?php

namespace Database\Seeders;

use App\Http\Controllers\Helpers\Wormholes;
use App\Models\SDE\SolarSystem;
use App\Models\Wormholes\WormholeStatics;
use Illuminate\Database\Seeder;

class WormholeStaticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This seeder will populate the different wormhole types.

        $file = 'database/json/wormhole_data/wormhole_statics.json';

        // Check the file exists

        if (file_exists($file)) {

            // File found, import.

            $this->command->info('Found Wormhole Statics...');
            $this->command->info('Importing....');

            $contents = file_get_contents($file);
            $wormhole_statics = json_decode($contents, true);

            $this->command->getOutput()->progressStart(count(json_decode($contents, true)));

            // Before Importing, TRUNCATE Existing DB.

            WormholeStatics::truncate();

            foreach ($wormhole_statics as $key => $wormhole) {

                // Get the System ID
                // $this->command->info($key);
                $system = SolarSystem::where('name', $key)->first();


                foreach ($wormhole as $static) {
                    $wormhole_type_id = Wormholes::type($static);

                    $insert = new WormholeStatics();
                    $insert->system_id = $system->system_id;
                    $insert->wormhole_type_id = $wormhole_type_id;
                    $insert->save();
                }

                $this->command->getOutput()->progressAdvance();
            }

            $this->command->getOutput()->progressFinish();
        } else {
            $this->command->info('File not found.');
        }
    }
}
