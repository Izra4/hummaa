<?php 

namespace App\Services\Contracts;

use App\Models\Question;
use Illuminate\Support\Collection;

interface QuestionServiceInterface
{
    public function getQuestionsForTryout(int $tryoutId, bool $randomize = false): Collection;
    
    public function getQuestionWithChoices(int $questionId): ?Question;
    
    public function getCorrectAnswer(int $questionId): ?int;
    
    public function validateAnswer(int $questionId, int $choiceId): bool;
    
    public function getQuestionExplanation(int $questionId): ?string;
    
    public function searchQuestions(string $query): Collection;
}
