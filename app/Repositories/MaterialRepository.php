<?php 

namespace App\Repositories;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Support\Collection;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function findById(int $id): ?Material
    {
        return Material::with(['category'])->find($id);
    }

    public function getAll(): Collection
    {
        return Material::with(['category'])->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return Material::byCategory($categoryId)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecent(int $days = 7): Collection
    {
        return Material::recent($days)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function search(string $query): Collection
    {
        return Material::where('judul', 'LIKE', "%{$query}%")
            ->orWhere('isi_materi', 'LIKE', "%{$query}%")
            ->with(['category'])
            ->get();
    }

    public function create(array $data): Material
    {
        return Material::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Material::where('materi_id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Material::destroy($id);
    }

    public function getAllCategories(): Collection
    {
        return MaterialCategory::withMaterialCount()
            ->orderBy('nama_kategori')
            ->get();
    }
}