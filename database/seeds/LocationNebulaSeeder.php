<?php

namespace Database\Seeders;

use App\Models\LocationNebula;
use Illuminate\Database\Seeder;

class LocationNebulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = 'database/json/gas/locationNebulaJoin.json';
        $contents = file_get_contents($file);
        $nebulas = json_decode($contents, true);
        LocationNebula::truncate();
        foreach ($nebulas as $n) {
            $new = new LocationNebula();
            $new->nebula_id = $n['nebula_id'];
            $new->location_id = $n['location_id'];
            $new->save();
        }
    }
}
