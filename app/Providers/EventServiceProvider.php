<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Custom Events
use App\Events\TryoutStarted;
use App\Events\TryoutCompleted;
use App\Events\AnswerSaved;
use App\Events\MaterialProgressUpdated;

// Custom Listeners
use App\Listeners\LogTryoutActivity;
use App\Listeners\CacheInvalidation;
use App\Listeners\SendTryoutNotifications;


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
        TryoutStarted::class => [
            LogTryoutActivity::class,
        ],
        
        TryoutCompleted::class => [
            LogTryoutActivity::class,
            CacheInvalidation::class,
            SendTryoutNotifications::class,
        ],
        
        MaterialProgressUpdated::class => [
            CacheInvalidation::class,
        ],
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
