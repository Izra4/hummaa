<?php 

namespace App\Services;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Services\Contracts\MaterialServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MaterialService implements MaterialServiceInterface
{
    public function __construct(
        private MaterialRepositoryInterface $materialRepository
    ) {}

    public function getAllCategories(): Collection
    {
        return Cache::remember('material_categories', 3600, function () {
            return $this->materialRepository->getAllCategories();
        });
    }

    public function getMaterialsByCategory(int $categoryId): Collection
    {
        return Cache::remember("materials_category_{$categoryId}", 1800, function () use ($categoryId) {
            return $this->materialRepository->getByCategory($categoryId);
        });
    }

    public function getMaterial(int $materialId): ?Material
    {
        return $this->materialRepository->findById($materialId);
    }

    public function updateUserProgress(int $materialId, int $userId, int $progressPercentage): bool
    {
        try {
            $material = Material::find($materialId);
            
            if (!$material) {
                return false;
            }

            $material->userProgress()->syncWithoutDetaching([
                $userId => [
                    'progress_percentage' => min(100, max(0, $progressPercentage)),
                    'last_accessed_at' => now(),
                ]
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserProgress(int $userId): Collection
    {
        return Material::whereHas('userProgress', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['userProgress' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, 'category'])->get();
    }

    public function markAsCompleted(int $materialId, int $userId): bool
    {
        return $this->updateUserProgress($materialId, $userId, 100);
    }

    public function getRecentMaterials(int $days = 7): Collection
    {
        return $this->materialRepository->getRecent($days);
    }

    public function searchMaterials(string $query): Collection
    {
        return $this->materialRepository->search($query);
    }
}