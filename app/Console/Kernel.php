<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application force push to update.
     *
     * @var array
     */
    protected $commands = [
        // Commands\UpdateNotifications::class,
        // Commands\UpdateAlliances::class,
        // Commands\UpdateCampaigns::class,
        // Commands\UpdateTimers::class,
        '\App\Console\Commands\Heartbeat\HeartbeatCommand',
        Commands\ClearRememberToken::class,
        Commands\zkill\ZkillUpdate::class,
        Commands\EVEApi\TranquiltyStatusCommand::class,
        Commands\External\EVEScoutWormholesCommand::class,
        Commands\CleanRoute::class,
        Commands\CleanUpSavedRoutes::class,
        Commands\RemoveReserve::class,
        Commands\SnapShotActivity::class,
        Commands\CleanUpEveStuffRoutes::class,
        Commands\UpdateMeto::class,
        Commands\removeRoles::class,
        Commands\updateShortestPaths::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:EveEsiStatus')->everyMinute()->withoutOverlapping();
        $schedule->command('update:sigRoutes')->everyMinute()->withoutOverlapping();
        $schedule->command('update:shortestPaths')->everyMinute()->withoutOverlapping();
        $schedule->command('update:zkill')->everyMinute()->withoutOverlapping();
        $schedule->command('check:gasstationjabber')->everyMinute()->withoutOverlapping();
        $schedule->command('snapshot:activity')->everyMinute();
        $schedule->command('eve:status')->everyMinute()->withoutOverlapping();
        $schedule->command('remove:reserve')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('external:evescout')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('activitylog:clean')->daily();
        // $schedule->command('heartbeat:run')->everyTenMinutes();
        $schedule->command('clean:sigsJob')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('clear:savedRoutes')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('clear:route')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        $schedule->command('clear:remembertoken')->twiceDaily(9, 21)->withoutOverlapping();
        $schedule->command('clean:evestuffroutes')->hourly()->withoutOverlapping();
        $schedule->command('update:userallianceandcorp')->hourly()->withoutOverlapping();
        $schedule->command('update:Metro')->hourly()->withoutOverlapping();
        $schedule->command('set:stats')->monthly();
        $schedule->command('clear:roles')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
