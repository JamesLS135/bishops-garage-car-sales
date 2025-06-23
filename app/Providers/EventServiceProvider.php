<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Purchase;
use App\Models\Sale; 
use App\Models\WorkDone; 
use App\Observers\CarObserver;
use App\Observers\PurchaseObserver;
use App\Observers\SaleObserver; 
use App\Observers\WorkDoneObserver; 
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
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Car::class => [CarObserver::class],
        Purchase::class => [PurchaseObserver::class],
        Sale::class => [SaleObserver::class],
        WorkDone::class => [WorkDoneObserver::class],
    ];


    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
