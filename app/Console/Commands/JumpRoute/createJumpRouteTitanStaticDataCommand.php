<?php

namespace App\Console\Commands\JumpRoute;

use App\Jobs\JumpRoute\calculateTitanRangeJob;
use App\Models\Connections\TitanBridges;
use App\Models\SDE\SolarSystem;
use Illuminate\Console\Command;

class createJumpRouteTitanStaticDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:generate:titan:jumps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the Static Data for Jump Routes for Titans from All Lowsec/Nullsec Systems.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->build_titan_systems();
    }

    public function build_titan_systems()
    {

        // Get all the systems.
        TitanBridges::truncate();

        $ignore_regions =
        [

            // Wormholes
            '11000001', //	A-R00001
            '11000002', //	A-R00002
            '11000003', //	A-R00003
            '11000004', //	B-R00004
            '11000005', //	B-R00005
            '11000006', //	B-R00006
            '11000007', //	B-R00007
            '11000008', //	B-R00008
            '11000009', //	C-R00009
            '11000010', //	C-R00010
            '11000011', //	C-R00011
            '11000012', //	C-R00012
            '11000013', //	C-R00013
            '11000014', //	C-R00014
            '11000015', //	C-R00015
            '11000016', //	D-R00016
            '11000017', //	D-R00017
            '11000018', //	D-R00018
            '11000019', //	D-R00019
            '11000020', //	D-R00020
            '11000021', //	D-R00021
            '11000022', //	D-R00022
            '11000023', //	D-R00023
            '11000024', //	E-R00024
            '11000025', //	E-R00025
            '11000026', //	E-R00026
            '11000027', //	E-R00027
            '11000028', //	E-R00028
            '11000029', //	E-R00029
            '11000030', //	F-R00030
            '11000031', //	G-R00031
            '11000032', //	H-R00032
            '11000033', //	K-R00033

            // Unknown
            '12000001', //	ADR01
            '12000002', //	ADR02
            '12000003', //	ADR03
            '12000004', //	ADR04
            '12000005', //	ADR05
            '13000001', //	PR-01

            // Jove
            '10000004', //	UUA-F4
            '10000017', //	J7HZ-F
            '10000019', //	A821-A

            // Pochven

            '10000070',

        ];

        // Source Systems.
        $systems = SolarSystem::whereNotIn('region_id', $ignore_regions)
        ->where('security', '<', 0.45)
        ->get();

        $this->output->progressStart(count($systems));

        // Check the distance of every system in eve againist target node.
        foreach ($systems as $system) {
            // Dispatch Job
            calculateTitanRangeJob::dispatch($system->system_id, $ignore_regions);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
