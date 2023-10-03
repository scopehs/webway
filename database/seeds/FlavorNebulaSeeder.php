<?php

namespace Database\Seeders;

use App\Models\FlavorNebula;
use Illuminate\Database\Seeder;

class FlavorNebulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = 'database/json/gas/flavorNebulaJoin.json';
        $contents = file_get_contents($file);
        $gas_flavors = json_decode($contents, true);
        FlavorNebula::truncate();
        foreach ($gas_flavors as $gas) {
            $new = new FlavorNebula();
            $new->gas_flavor_id = $gas['gas_flavor_id'];
            $new->nebula_id = $gas['nebula_id'];
            $new->save();
        }
    }
}
