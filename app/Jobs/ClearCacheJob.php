<?php 

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ClearCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $cacheKeys
    ) {}

    public function handle(): void
    {
        foreach ($this->cacheKeys as $key) {
            if (str_contains($key, '*')) {
                // Clear cache keys matching pattern
                Cache::flush(); // For simplicity, clear all cache
            } else {
                Cache::forget($key);
            }
        }
    }
}
