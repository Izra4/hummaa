<?php

// app/Repositories/Contracts/MaterialRepositoryInterface.php
namespace App\Repositories\Contracts;

use App\Models\Material;
use Illuminate\Support\Collection;

interface MaterialRepositoryInterface
{
    /**
     * Find material by ID
     */
    public function findById(int $id): ?Material;

    /**
     * Get all materials
     */
    public function getAll(): Collection;

    /**
     * Get materials by category ID
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get recent materials
     */
    public function getRecent(int $days = 7): Collection;

    /**
     * Search materials by query
     */
    public function search(string $query): Collection;

    /**
     * Create new material
     */
    public function create(array $data): Material;

    /**
     * Update material by ID
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete material by ID
     */
    public function delete(int $id): bool;

    /**
     * Get all material categories
     */
    public function getAllCategories(): Collection;
}