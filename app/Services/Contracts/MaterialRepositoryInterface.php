<?php 

namespace App\Repositories\Contracts;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Support\Collection;

interface MaterialRepositoryInterface
{
    public function findById(int $id): ?Material;
    
    public function getAll(): Collection;
    
    public function getByCategory(int $categoryId): Collection;
    
    public function getRecent(int $days = 7): Collection;
    
    public function search(string $query): Collection;
    
    public function create(array $data): Material;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function getAllCategories(): Collection;
}
