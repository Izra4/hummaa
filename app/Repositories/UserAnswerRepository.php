<?php 

namespace App\Repositories;

use App\Models\UserAnswer;
use App\Repositories\Contracts\UserAnswerRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserAnswerRepository implements UserAnswerRepositoryInterface
{
    public function findById(int $id): ?UserAnswer
    {
        return UserAnswer::with(['result', 'question', 'choice'])->find($id);
    }

    public function create(array $data): UserAnswer
    {
        return UserAnswer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return UserAnswer::where('jawaban_pengguna_id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return UserAnswer::destroy($id);
    }

    public function getByResult(int $resultId): Collection
    {
        return UserAnswer::where('hasil_id', $resultId)
            ->with(['question', 'choice'])
            ->get();
    }

    public function getByQuestion(int $resultId, int $questionId): ?UserAnswer
    {
        return UserAnswer::where('hasil_id', $resultId)
            ->where('soal_id', $questionId)
            ->with(['question', 'choice'])
            ->first();
    }

    public function bulkCreate(array $answers): bool
    {
        try {
            UserAnswer::insert($answers);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function bulkUpdate(int $resultId, array $answers): bool
    {
        try {
            DB::transaction(function () use ($resultId, $answers) {
                foreach ($answers as $questionId => $choiceId) {
                    UserAnswer::updateOrCreate(
                        [
                            'hasil_id' => $resultId,
                            'soal_id' => $questionId,
                        ],
                        [
                            'pilihan_id' => $choiceId,
                        ]
                    );
                }
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAnswerStatistics(int $resultId): array
    {
        $answers = $this->getByResult($resultId);

        return [
            'total_questions' => $answers->count(),
            'answered' => $answers->filter(fn($answer) => $answer->isAnswered())->count(),
            'unanswered' => $answers->filter(fn($answer) => !$answer->isAnswered())->count(),
            'correct' => $answers->filter(fn($answer) => $answer->isCorrect())->count(),
            'incorrect' => $answers->filter(fn($answer) => $answer->isAnswered() && !$answer->isCorrect())->count(),
        ];
    }
}