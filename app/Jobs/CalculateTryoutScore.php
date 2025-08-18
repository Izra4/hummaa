<?php 

namespace App\Jobs;

use App\Models\TryoutResult;
use App\Services\Contracts\TryoutServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CalculateTryoutScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public TryoutResult $tryoutResult
    ) {}

    public function handle(TryoutServiceInterface $tryoutService): void
    {
        try {
            $score = $tryoutService->calculateScore($this->tryoutResult);
            
            $this->tryoutResult->update([
                'skor_akhir' => $score,
            ]);

            Log::info("Calculated score {$score} for tryout result {$this->tryoutResult->hasil_id}");
        } catch (\Exception $e) {
            Log::error("Failed to calculate score for tryout result {$this->tryoutResult->hasil_id}: " . $e->getMessage());
            throw $e;
        }
    }
}