<?php 

namespace App\Repositories\Contracts;

use App\Models\Question;
use Illuminate\Support\Collection;

interface QuestionRepositoryInterface
{
    public function findById(int $id): ?Question;
    
    public function getForTryout(int $tryoutId, bool $randomize = false): Collection;
    
    public function getWithChoices(int $id): ?Question;
    
    public function search(string $query): Collection;
    
    public function create(array $data): Question;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function attachToTryout(int $questionId, int $tryoutId): bool;
    
    public function detachFromTryout(int $questionId, int $tryoutId): bool;
}
