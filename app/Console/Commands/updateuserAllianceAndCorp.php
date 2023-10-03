<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUserAlliance;
use App\Jobs\UpdateUserCorps;
use App\Models\EVE\Alliances;
use App\Models\EVE\Characters;
use App\Models\EVE\Corporations;
use Illuminate\Console\Command;

class updateuserAllianceAndCorp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:userallianceandcorp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check all users corp and alliance IDs, and add any missing to the database';

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
        $corpIDs = Characters::pluck('corporation_id')->unique();
        $allianceIDs = Characters::pluck('alliance_id')->unique();

        foreach ($allianceIDs as $allianceID) {
            if ($allianceID != null) {
                $check = Alliances::where('id', $allianceID)->first();
                if (! $check) {
                    UpdateUserAlliance::dispatch($allianceID);
                }
            }
        }

        foreach ($corpIDs as $corpID) {
            $check = Corporations::where('id', $corpID)->first();
            if (! $check) {
                UpdateUserCorps::dispatch($corpID);
            }
        }
    }
}
