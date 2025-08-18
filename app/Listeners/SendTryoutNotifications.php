<?php

namespace App\Listeners;

use App\Events\TryoutCompleted;
use App\Notifications\TryoutCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTryoutNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TryoutCompleted $event): void
    {
        // Send notification to user
        $event->tryoutResult->user->notify(
            new TryoutCompletedNotification($event->tryoutResult)
        );

        // You could also send notifications to admins, etc.
    }
}