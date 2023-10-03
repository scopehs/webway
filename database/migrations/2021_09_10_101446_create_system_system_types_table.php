<?php

use App\Models\SDE\SolarSystem;
use App\Models\SystemSystemType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSystemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_system_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->foreignId('system_type_id');
        });

        $systems = SolarSystem::where('region_id', '>', 11000000)->where('region_id', '<', 11000004)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 1]);
        }

        $systems = SolarSystem::where('region_id', 11000033)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 1]);
        }

        $systems = SolarSystem::where('region_id', '>', 11000003)->where('region_id', '<', 11000009)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 2]);
        }

        $systems = SolarSystem::where('region_id', '>', 11000008)->where('region_id', '<', 11000016)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 3]);
        }

        $systems = SolarSystem::where('region_id', '>', 11000015)->where('region_id', '<', 11000024)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 4]);
        }

        $systems = SolarSystem::where('region_id', '>', 11000023)->where('region_id', '<', 11000030)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 5]);
        }

        $systems = SolarSystem::where('region_id', 11000030)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 6]);
        }

        $systems = SolarSystem::where('region_id', 11000031)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 12]);
        }

        $systems = SolarSystem::where('region_id', 11000032)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 13]);
        }

        $systems = SolarSystem::where('system_id', 31000001)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 14]);
        }

        $systems = SolarSystem::where('system_id', 31000002)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 15]);
        }

        $systems = SolarSystem::where('system_id', 31000003)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 16]);
        }

        $systems = SolarSystem::where('system_id', 31000004)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 17]);
        }

        $systems = SolarSystem::where('system_id', 31000006)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 18]);
        }

        $systems = SolarSystem::where('region_id', 10000070)->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 25]);
        }

        $systems = SolarSystem::where('region_id', 10000001)
            ->orWhere('region_id', 10000002)
            ->orWhere('region_id', 10000016)
            ->orWhere('region_id', 10000020)
            ->orWhere('region_id', 10000028)
            ->orWhere('region_id', 10000030)
            ->orWhere('region_id', 10000032)
            ->orWhere('region_id', 10000033)
            ->orWhere('region_id', 10000036)
            ->orWhere('region_id', 10000037)
            ->orWhere('region_id', 10000038)
            ->orWhere('region_id', 10000042)
            ->orWhere('region_id', 10000043)
            ->orWhere('region_id', 10000044)
            ->orWhere('region_id', 10000048)
            ->orWhere('region_id', 10000049)
            ->orWhere('region_id', 10000052)
            ->orWhere('region_id', 10000054)
            ->orWhere('region_id', 10000064)
            ->orWhere('region_id', 10000065)
            ->orWhere('region_id', 10000067)
            ->orWhere('region_id', 10000068)
            ->orWhere('region_id', 10000069)
            ->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 7]);
        }

        $systems = SolarSystem::where('region_id', 10000003)
            ->orWhere('region_id', 10000005)
            ->orWhere('region_id', 10000006)
            ->orWhere('region_id', 10000007)
            ->orWhere('region_id', 10000008)
            ->orWhere('region_id', 10000009)
            ->orWhere('region_id', 10000010)
            ->orWhere('region_id', 10000011)
            ->orWhere('region_id', 10000012)
            ->orWhere('region_id', 10000013)
            ->orWhere('region_id', 10000014)
            ->orWhere('region_id', 10000015)
            ->orWhere('region_id', 10000018)
            ->orWhere('region_id', 10000021)
            ->orWhere('region_id', 10000022)
            ->orWhere('region_id', 10000023)
            ->orWhere('region_id', 10000025)
            ->orWhere('region_id', 10000027)
            ->orWhere('region_id', 10000029)
            ->orWhere('region_id', 10000031)
            ->orWhere('region_id', 10000034)
            ->orWhere('region_id', 10000035)
            ->orWhere('region_id', 10000039)
            ->orWhere('region_id', 10000040)
            ->orWhere('region_id', 10000041)
            ->orWhere('region_id', 10000045)
            ->orWhere('region_id', 10000046)
            ->orWhere('region_id', 10000047)
            ->orWhere('region_id', 10000050)
            ->orWhere('region_id', 10000051)
            ->orWhere('region_id', 10000053)
            ->orWhere('region_id', 10000055)
            ->orWhere('region_id', 10000056)
            ->orWhere('region_id', 10000057)
            ->orWhere('region_id', 10000058)
            ->orWhere('region_id', 10000059)
            ->orWhere('region_id', 10000060)
            ->orWhere('region_id', 10000061)
            ->orWhere('region_id', 10000062)
            ->orWhere('region_id', 10000063)
            ->orWhere('region_id', 10000066)
            ->get();
        foreach ($systems as $system) {
            SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 9]);
        }

        $systems = SolarSystem::where('security', '>', 0.0)->where('security', '<', 0.5)->get();
        foreach ($systems as $system) {
            $check = SystemSystemType::where('system_id', $system->system_id)->first();
            if ($check) {
                SystemSystemType::where('system_id', $system->system_id)->update(['system_type_id' => 8]);
            } else {
                SystemSystemType::create(['system_id' => $system->system_id, 'system_type_id' => 8]);
            }
        }
    }

    /**
     * Reverse the migraffons.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_system_types');
    }
}
