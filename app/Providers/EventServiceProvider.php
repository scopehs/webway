<?php

namespace App\Providers;

use App\Events\NotificationChanged;
use App\Events\NotificationNew;
use App\Listeners\SendNotificationChange;
use App\Listeners\SendNotificationNew;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        NotificationChanged::class => [
            SendNotificationChange::class,
        ],

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        NotificationNew::class => [
            SendNotificationNew::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
