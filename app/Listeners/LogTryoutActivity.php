<?php 

namespace App\Listeners;

use App\Events\TryoutStarted;
use App\Events\TryoutCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogTryoutActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TryoutStarted|TryoutCompleted $event): void
    {
        $action = $event instanceof TryoutStarted ? 'started' : 'completed';
        
        Log::info("User {$event->tryoutResult->user_id} {$action} tryout {$event->tryoutResult->tryout_id}", [
            'user_id' => $event->tryoutResult->user_id,
            'tryout_id' => $event->tryoutResult->tryout_id,
            'result_id' => $event->tryoutResult->hasil_id,
            'mode' => $event->tryoutResult->mode,
            'timestamp' => now(),
        ]);
    }
}