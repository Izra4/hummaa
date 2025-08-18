<?php 

namespace App\Jobs;

use App\Services\Contracts\TryoutServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoSubmitExpiredTryouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(TryoutServiceInterface $tryoutService): void
    {
        try {
            $submittedCount = $tryoutService->autoSubmitExpiredTryouts();
            
            if ($submittedCount > 0) {
                Log::info("Auto-submitted {$submittedCount} expired tryout sessions");
            }
        } catch (\Exception $e) {
            Log::error('Failed to auto-submit expired tryouts: ' . $e->getMessage());
            throw $e;
        }
    }
}