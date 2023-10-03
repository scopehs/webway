<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use utils\Helper\Helper;

class clearAllRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:allRoles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Helper::clearRemember();
        $users = User::get();
        foreach ($users as $user) {
            $user->removeRole(30); // Director
            $user->removeRole(36); // Skirmish FC
            $user->removeRole(37); // gsol
            $user->removeRole(38); // scout
            $user->removeRole(39); // ops
            $user->removeRole(40); // recon
            $user->removeRole(41); // recon-1
            $user->removeRole(35); // coord
            $user->removeRole(29); // pathfinders
            $user->removeRole(42); // pathfinders-L
            $user->removeRole(43); // Genesis
            $user->removeRole(44); // Base
        }
    }
}
