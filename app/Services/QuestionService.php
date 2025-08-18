<?php 

namespace App\Services;

use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Services\Contracts\QuestionServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class QuestionService implements QuestionServiceInterface
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository
    ) {}

    public function getQuestionsForTryout(int $tryoutId, bool $randomize = false): Collection
    {
        $cacheKey = "questions_tryout_{$tryoutId}" . ($randomize ? '_random' : '');
        $cacheTtl = $randomize ? 300 : 1800; // 5 min for randomized, 30 min for ordered

        return Cache::remember($cacheKey, $cacheTtl, function () use ($tryoutId, $randomize) {
            return $this->questionRepository->getForTryout($tryoutId, $randomize);
        });
    }

    public function getQuestionWithChoices(int $questionId): ?Question
    {
        return Cache::remember("question_with_choices_{$questionId}", 1800, function () use ($questionId) {
            return $this->questionRepository->getWithChoices($questionId);
        });
    }

    public function getCorrectAnswer(int $questionId): ?int
    {
        $question = $this->getQuestionWithChoices($questionId);
        return $question?->getCorrectChoiceId();
    }

    public function validateAnswer(int $questionId, int $choiceId): bool
    {
        $question = $this->getQuestionWithChoices($questionId);
        return $question?->isCorrectAnswer($choiceId) ?? false;
    }

    public function getQuestionExplanation(int $questionId): ?string
    {
        $question = $this->questionRepository->findById($questionId);
        return $question?->pembahasan;
    }

    public function searchQuestions(string $query): Collection
    {
        return $this->questionRepository->search($query);
    }
}