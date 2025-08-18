<?php 

namespace App\Listeners;

use App\Events\TryoutCompleted;
use App\Events\MaterialProgressUpdated;
use App\Jobs\ClearCacheJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CacheInvalidation implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TryoutCompleted|MaterialProgressUpdated $event): void
    {
        $cacheKeys = [];

        if ($event instanceof TryoutCompleted) {
            $cacheKeys = [
                "user_tryout_history_{$event->tryoutResult->user_id}",
                "tryout_stats_{$event->tryoutResult->tryout_id}",
            ];
        }

        if ($event instanceof MaterialProgressUpdated) {
            $cacheKeys = [
                "user_material_progress_{$event->user->id}",
                "material_stats_{$event->material->materi_id}",
            ];
        }

        if (!empty($cacheKeys)) {
            ClearCacheJob::dispatch($cacheKeys);
        }
    }
}