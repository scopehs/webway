<?php

namespace Database\Seeders;

use App\Models\Nebula;
use Illuminate\Database\Seeder;

class NebulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = 'database/json/gas/nebulaType.json';
        $contents = file_get_contents($file);
        $nebulas = json_decode($contents, true);
        Nebula::truncate();
        foreach ($nebulas as $n) {
            $damageType = $n['damage_type'] ?? null;
            // if ($n['damage_type']) {
            //     $damageType = json_encode($n['damage_type']);
            // }
            $new = new Nebula();
            $new->id = $n['id'];
            $new->units = $n['units'];
            $new->name = $n['name'];
            $new->damage = $n['damage'];
            $new->damage_type = $damageType;
            $new->npc = $n['npc'];
            $new->dead_space = $n['dead_space'];
            $new->ninja = $n['ninja'];
            $new->jedi = $n['jedi'];
            $new->save();
        }
    }
}
