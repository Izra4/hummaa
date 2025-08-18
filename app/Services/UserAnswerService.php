<?php 

namespace App\Services;

use App\Models\UserAnswer;
use App\Repositories\Contracts\UserAnswerRepositoryInterface;
use App\Services\Contracts\UserAnswerServiceInterface;
use Illuminate\Support\Collection;

class UserAnswerService implements UserAnswerServiceInterface
{
    public function __construct(
        private UserAnswerRepositoryInterface $userAnswerRepository
    ) {}

    public function saveUserAnswer(int $resultId, int $questionId, ?int $choiceId): UserAnswer
    {
        // Find existing answer or create new one
        $existingAnswer = $this->userAnswerRepository->getByQuestion($resultId, $questionId);

        if ($existingAnswer) {
            $this->userAnswerRepository->update($existingAnswer->jawaban_pengguna_id, [
                'pilihan_id' => $choiceId
            ]);
            return $this->userAnswerRepository->findById($existingAnswer->jawaban_pengguna_id);
        }

        return $this->userAnswerRepository->create([
            'hasil_id' => $resultId,
            'soal_id' => $questionId,
            'pilihan_id' => $choiceId,
        ]);
    }

    public function getUserAnswers(int $resultId): Collection
    {
        return $this->userAnswerRepository->getByResult($resultId);
    }

    public function getAnswerForQuestion(int $resultId, int $questionId): ?UserAnswer
    {
        return $this->userAnswerRepository->getByQuestion($resultId, $questionId);
    }

    public function bulkSaveAnswers(int $resultId, array $answers): bool
    {
        return $this->userAnswerRepository->bulkUpdate($resultId, $answers);
    }

    public function deleteUserAnswer(int $resultId, int $questionId): bool
    {
        $answer = $this->userAnswerRepository->getByQuestion($resultId, $questionId);
        
        if (!$answer) {
            return false;
        }

        return $this->userAnswerRepository->delete($answer->jawaban_pengguna_id);
    }

    public function getAnswerStatistics(int $resultId): array
    {
        return $this->userAnswerRepository->getAnswerStatistics($resultId);
    }
}