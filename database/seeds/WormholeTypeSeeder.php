<?php

namespace Database\Seeders;

use App\Models\Wormholes\WormholeType;
use Illuminate\Database\Seeder;

class WormholeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This seeder will populate the different wormhole types.

        $file = 'database/json/wormhole_data/wormhole_types.json';

        // Check the file exists

        if (file_exists($file)) {

            // File found, import.

            $this->command->info('Found Wormhole Types...');
            $this->command->info('Importing....');

            $contents = file_get_contents($file);
            $wormhole_types = json_decode($contents);

            $this->command->getOutput()->progressStart(count(json_decode($contents, true)));

            // Before Importing, TRUNCATE Existing DB.

            WormholeType::truncate();

            foreach ($wormhole_types as $key => $wormhole) {
                $this->command->getOutput()->progressAdvance();

                $insert = new WormholeType();
                $insert->wormhole_type = $key;
                $insert->life = $wormhole->life;
                $insert->leads_to = $wormhole->leadsTo;
                $insert->mass = $wormhole->mass;
                $insert->jump = $wormhole->jump;
                $insert->wandering = $wormhole->wandering;
                $insert->regen = $wormhole->regen;
                $insert->save();
            }


            $k162 = WormholeType::where('wormhole_type', 'K162')->first();
            $k162->id = 420;
            $k162->save();
            $this->command->getOutput()->progressFinish();
        } else {
            $this->command->info('File not found.');
        }
    }
}
