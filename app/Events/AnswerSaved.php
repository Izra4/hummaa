<?php 

namespace App\Events;

use App\Models\UserAnswer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UserAnswer $userAnswer
    ) {}
}