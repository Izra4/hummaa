<?php 

namespace App\Repositories\Contracts;

use App\Models\Tryout;
use App\Models\TryoutResult;
use Illuminate\Support\Collection;

interface TryoutRepositoryInterface
{
    public function findById(int $id): ?Tryout;
    
    public function getPublished(): Collection;
    
    public function getByType(string $type): Collection;
    
    public function getWithStats(): Collection;
    
    public function create(array $data): Tryout;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
}