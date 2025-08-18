<?php

namespace App\Services\Contracts;

use App\Models\Tryout;
use App\Models\TryoutResult;
use App\Models\User;
use Illuminate\Support\Collection;

interface TryoutServiceInterface
{
    public function getPublishedTryouts(): Collection;
    
    public function getTryoutsByType(string $type): Collection;
    
    public function startTryout(int $tryoutId, int $userId, string $mode = 'tryout'): TryoutResult;
    
    public function getTryoutSession(int $resultId): ?TryoutResult;
    
    public function saveAnswer(int $resultId, int $questionId, ?int $choiceId): bool;
    
    public function submitTryout(int $resultId): TryoutResult;
    
    public function autoSubmitExpiredTryouts(): int;
    
    public function calculateScore(TryoutResult $result): float;
    
    public function getRandomizedQuestions(int $tryoutId): Collection;
    
    public function getUserTryoutHistory(int $userId, int $limit = 10): Collection;
}