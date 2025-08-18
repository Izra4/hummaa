<?php 

namespace App\Services\Contracts;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Support\Collection;

interface MaterialServiceInterface
{
    public function getAllCategories(): Collection;
    
    public function getMaterialsByCategory(int $categoryId): Collection;
    
    public function getMaterial(int $materialId): ?Material;
    
    public function updateUserProgress(int $materialId, int $userId, int $progressPercentage): bool;
    
    public function getUserProgress(int $userId): Collection;
    
    public function markAsCompleted(int $materialId, int $userId): bool;
    
    public function getRecentMaterials(int $days = 7): Collection;
    
    public function searchMaterials(string $query): Collection;
}