<?php

namespace App\Events;

use App\Models\TryoutResult;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TryoutStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public TryoutResult $tryoutResult
    ) {}
}