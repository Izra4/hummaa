<?php

// app/Repositories/Contracts/TryoutResultRepositoryInterface.php
namespace App\Repositories\Contracts;

use App\Models\TryoutResult;
use Illuminate\Support\Collection;

interface TryoutResultRepositoryInterface
{
    /**
     * Find tryout result by ID
     */
    public function findById(int $id): ?TryoutResult;

    /**
     * Create new tryout result
     */
    public function create(array $data): TryoutResult;

    /**
     * Update tryout result by ID
     */
    public function update(int $id, array $data): bool;

    /**
     * Get tryout results by user with optional limit
     */
    public function getByUser(int $userId, int $limit = null): Collection;

    /**
     * Get in progress tryout sessions
     */
    public function getInProgress(): Collection;

    /**
     * Get expired tryout sessions
     */
    public function getExpiredSessions(): Collection;

    /**
     * Get results by tryout ID
     */
    public function getByTryout(int $tryoutId): Collection;

    /**
     * Get completed attempts by user
     */
    public function getCompletedByUser(int $userId): Collection;

    /**
     * Get user statistics
     */
    public function getUserStats(int $userId): array;
}