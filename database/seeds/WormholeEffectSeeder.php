<?php

namespace Database\Seeders;

use App\Models\Wormholes\WormholeEffects;
use Illuminate\Database\Seeder;

class WormholeEffectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This seeder will populate the different wormhole types.

        $file = 'database/json/wormhole_data/wormhole_effects.json';

        // Check the file exists

        if (file_exists($file)) {

             // File found, import.

            $this->command->info('Found Wormhole Effects...');
            $this->command->info('Importing....');

            $contents = file_get_contents($file);
            $wormhole_effects = json_decode($contents);

            $this->command->getOutput()->progressStart(count(json_decode($contents, true)));

            // Before Importing, TRUNCATE Existing DB.

            WormholeEffects::truncate();

            foreach ($wormhole_effects as $wormhole) {
                $insert = new WormholeEffects();
                $insert->type_id = $wormhole->type_id;
                $insert->hole_type = $wormhole->hole_type;
                $insert->effect = $wormhole->effect;
                $insert->icon = $wormhole->icon;
                $insert->C1 = $wormhole->c1;
                $insert->C2 = $wormhole->c2;
                $insert->C3 = $wormhole->c3;
                $insert->C4 = $wormhole->c4;
                $insert->C5 = $wormhole->c5;
                $insert->C6 = $wormhole->c6;
                $insert->save();

                $this->command->getOutput()->progressAdvance();
            }

            $this->command->getOutput()->progressFinish();
        } else {
            $this->command->info('File not found.');
        }
    }
}
