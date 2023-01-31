<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\Parcel\ParcelCreated::class => [
            \App\Listeners\Parcel\SendParcelUpdateToBiker::class,
        ],
        \App\Events\Parcel\ParcelPickedUp::class => [
            \App\Listeners\Parcel\SendParcelUpdateToCustomer::class,
        ],
        \App\Events\Parcel\ParcelDilevered::class => [
            \App\Listeners\Parcel\SendParcelUpdateToCustomer::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
