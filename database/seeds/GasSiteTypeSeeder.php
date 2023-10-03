<?php

namespace Database\Seeders;

use App\Models\GasFlavor;
use Illuminate\Database\Seeder;

class GasSiteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = 'database/json/gas/siteTypes.json';
        $contents = file_get_contents($file);
        $gas_flavors = json_decode($contents, true);
        GasFlavor::truncate();
        foreach ($gas_flavors as $gas) {
            $new = new GasFlavor();
            $new->id = $gas['id'];
            $new->name = $gas['name'];
            $new->save();
        }
    }
}
