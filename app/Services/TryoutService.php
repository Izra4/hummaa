<?php

namespace App\Services;

use App\Models\Tryout;
use App\Models\TryoutResult;
use App\Repositories\Contracts\TryoutRepositoryInterface;
use App\Repositories\Contracts\TryoutResultRepositoryInterface;
use App\Repositories\Contracts\UserAnswerRepositoryInterface;
use App\Services\Contracts\TryoutServiceInterface;
use App\Services\Contracts\UserAnswerServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TryoutService implements TryoutServiceInterface
{
    public function __construct(
        private TryoutRepositoryInterface $tryoutRepository,
        private TryoutResultRepositoryInterface $resultRepository,
        private UserAnswerServiceInterface $userAnswerService
    ) {}

    public function getPublishedTryouts(): Collection
    {
        return Cache::remember('published_tryouts', 3600, function () {
            return $this->tryoutRepository->getPublished();
        });
    }

    public function getTryoutsByType(string $type): Collection
    {
        return Cache::remember("tryouts_type_{$type}", 3600, function () use ($type) {
            return $this->tryoutRepository->getByType($type);
        });
    }

    public function startTryout(int $tryoutId, int $userId, string $mode = 'tryout'): TryoutResult
    {
        // Check if user has active session
        $activeSession = $this->resultRepository->getByUser($userId)
            ->where('status', 'sedang dikerjakan')
            ->where('tryout_id', $tryoutId)
            ->first();

        if ($activeSession) {
            return $activeSession;
        }

        // Create new session
        $result = $this->resultRepository->create([
            'user_id' => $userId,
            'tryout_id' => $tryoutId,
            'waktu_mulai' => now(),
            'status' => 'sedang dikerjakan',
            'mode' => $mode,
        ]);

        // Initialize user answers
        $tryout = $this->tryoutRepository->findById($tryoutId);
        $questions = $this->getRandomizedQuestions($tryoutId);
        
        $answers = [];
        foreach ($questions as $question) {
            $answers[] = [
                'hasil_id' => $result->hasil_id,
                'soal_id' => $question->soal_id,
                'pilihan_id' => null,
            ];
        }

        $this->userAnswerService->bulkSaveAnswers($result->hasil_id, $answers);

        return $result;
    }

    public function getTryoutSession(int $resultId): ?TryoutResult
    {
        return $this->resultRepository->findById($resultId);
    }

    public function saveAnswer(int $resultId, int $questionId, ?int $choiceId): bool
    {
        try {
            $this->userAnswerService->saveUserAnswer($resultId, $questionId, $choiceId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function submitTryout(int $resultId): TryoutResult
    {
        $result = $this->resultRepository->findById($resultId);
        
        if (!$result || $result->status === 'selesai') {
            throw new \Exception('Invalid tryout session');
        }

        DB::transaction(function () use ($result) {
            $score = $this->calculateScore($result);
            
            $this->resultRepository->update($result->hasil_id, [
                'waktu_selesai' => now(),
                'skor_akhir' => $score,
                'status' => 'selesai',
            ]);
        });

        return $this->resultRepository->findById($resultId);
    }

    public function autoSubmitExpiredTryouts(): int
    {
        $expiredSessions = $this->resultRepository->getExpiredSessions();
        $count = 0;

        foreach ($expiredSessions as $session) {
            try {
                $this->submitTryout($session->hasil_id);
                $count++;
            } catch (\Exception $e) {
                // Log error but continue processing other sessions
                Log::error("Failed to auto-submit tryout {$session->hasil_id}: " . $e->getMessage());
            }
        }

        return $count;
    }

    public function calculateScore(TryoutResult $result): float
    {
        $stats = $this->userAnswerService->getAnswerStatistics($result->hasil_id);
        
        if ($stats['total_questions'] === 0) {
            return 0;
        }

        // Simple scoring: (correct answers / total questions) * 100
        return round(($stats['correct'] / $stats['total_questions']) * 100, 2);
    }

    public function getRandomizedQuestions(int $tryoutId): Collection
    {
        return Cache::remember("tryout_questions_{$tryoutId}", 1800, function () use ($tryoutId) {
            $tryout = $this->tryoutRepository->findById($tryoutId);
            return $tryout->getRandomizedQuestions();
        });
    }

    public function getUserTryoutHistory(int $userId, int $limit = 10): Collection
    {
        return $this->resultRepository->getByUser($userId, $limit);
    }
}