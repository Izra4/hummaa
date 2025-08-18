<?php 

namespace App\Repositories\Contracts;

use App\Models\TryoutResult;
use Illuminate\Support\Collection;

interface TryoutResultRepositoryInterface
{
    public function findById(int $id): ?TryoutResult;
    
    public function create(array $data): TryoutResult;
    
    public function update(int $id, array $data): bool;
    
    public function getByUser(int $userId, int $limit = null): Collection;
    
    public function getInProgress(): Collection;
    
    public function getExpiredSessions(): Collection;
    
    public function getByTryout(int $tryoutId): Collection;
    
    public function getCompletedByUser(int $userId): Collection;
    
    public function getUserStats(int $userId): array;
}
