<?php

namespace App\Repositories\Contracts;

use App\Models\Tryout;
use Illuminate\Support\Collection;

interface TryoutRepositoryInterface
{
    /**
     * Find tryout by ID with relations
     */
    public function findById(int $id): ?Tryout;

    /**
     * Get all published tryouts
     */
    public function getPublished(): Collection;

    /**
     * Get tryouts by type
     */
    public function getByType(string $type): Collection;

    /**
     * Get tryouts with statistics
     */
    public function getWithStats(): Collection;

    /**
     * Create new tryout
     */
    public function create(array $data): Tryout;

    /**
     * Update tryout by ID
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete tryout by ID
     */
    public function delete(int $id): bool;
}