<?php 

namespace App\Services\Contracts;

use App\Models\TryoutResult;
use App\Models\UserAnswer;
use Illuminate\Support\Collection;

interface UserAnswerServiceInterface
{
    public function saveUserAnswer(int $resultId, int $questionId, ?int $choiceId): UserAnswer;
    
    public function getUserAnswers(int $resultId): Collection;
    
    public function getAnswerForQuestion(int $resultId, int $questionId): ?UserAnswer;
    
    public function bulkSaveAnswers(int $resultId, array $answers): bool;
    
    public function deleteUserAnswer(int $resultId, int $questionId): bool;
    
    public function getAnswerStatistics(int $resultId): array;
}

