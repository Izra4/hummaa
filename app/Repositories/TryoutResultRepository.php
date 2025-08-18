<?php 

namespace App\Repositories;

use App\Models\TryoutResult;
use App\Repositories\Contracts\TryoutResultRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TryoutResultRepository implements TryoutResultRepositoryInterface
{
    public function findById(int $id): ?TryoutResult
    {
        return TryoutResult::with(['user', 'tryout', 'userAnswers', 'userAnswers.question', 'userAnswers.choice'])
            ->find($id);
    }

    public function create(array $data): TryoutResult
    {
        return TryoutResult::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return TryoutResult::where('hasil_id', $id)->update($data);
    }

    public function getByUser(int $userId, int $limit = null): Collection
    {
        $query = TryoutResult::with(['tryout'])
            ->byUser($userId)
            ->orderBy('waktu_mulai', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getInProgress(): Collection
    {
        return TryoutResult::inProgress()
            ->with(['user', 'tryout'])
            ->get();
    }

    public function getExpiredSessions(): Collection
    {
        return TryoutResult::inProgress()
            ->with(['tryout'])
            ->get()
            ->filter(function ($result) {
                return $result->isExpired();
            });
    }

    public function getByTryout(int $tryoutId): Collection
    {
        return TryoutResult::where('tryout_id', $tryoutId)
            ->with(['user'])
            ->orderBy('skor_akhir', 'desc')
            ->get();
    }

    public function getCompletedByUser(int $userId): Collection
    {
        return TryoutResult::byUser($userId)
            ->completed()
            ->with(['tryout'])
            ->orderBy('waktu_selesai', 'desc')
            ->get();
    }

    public function getUserStats(int $userId): array
    {
        return [
            'total_attempts' => TryoutResult::byUser($userId)->count(),
            'completed_attempts' => TryoutResult::byUser($userId)->completed()->count(),
            'average_score' => TryoutResult::byUser($userId)->completed()->avg('skor_akhir'),
            'best_score' => TryoutResult::byUser($userId)->completed()->max('skor_akhir'),
            'total_time_spent' => TryoutResult::byUser($userId)->completed()->get()->sum(function ($result) {
                return $result->getDurationInMinutes();
            })
        ];
    }
}