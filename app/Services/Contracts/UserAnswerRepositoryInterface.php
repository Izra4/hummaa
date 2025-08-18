<?php

namespace App\Repositories\Contracts;

use App\Models\UserAnswer;
use Illuminate\Support\Collection;

interface UserAnswerRepositoryInterface
{
    public function findById(int $id): ?UserAnswer;
    
    public function create(array $data): UserAnswer;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function getByResult(int $resultId): Collection;
    
    public function getByQuestion(int $resultId, int $questionId): ?UserAnswer;
    
    public function bulkCreate(array $answers): bool;
    
    public function bulkUpdate(int $resultId, array $answers): bool;
    
    public function getAnswerStatistics(int $resultId): array;
}
