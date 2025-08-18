<?php

// app/Repositories/Contracts/UserAnswerRepositoryInterface.php
namespace App\Repositories\Contracts;

use App\Models\UserAnswer;
use Illuminate\Support\Collection;

interface UserAnswerRepositoryInterface
{
    /**
     * Find user answer by ID
     */
    public function findById(int $id): ?UserAnswer;

    /**
     * Create new user answer
     */
    public function create(array $data): UserAnswer;

    /**
     * Update user answer by ID
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete user answer by ID
     */
    public function delete(int $id): bool;

    /**
     * Get user answers by tryout result ID
     */
    public function getByResult(int $resultId): Collection;

    /**
     * Get specific user answer by result and question ID
     */
    public function getByQuestion(int $resultId, int $questionId): ?UserAnswer;

    /**
     * Create multiple user answers in bulk
     */
    public function bulkCreate(array $answers): bool;

    /**
     * Update multiple user answers in bulk
     */
    public function bulkUpdate(int $resultId, array $answers): bool;

    /**
     * Get answer statistics for a tryout result
     */
    public function getAnswerStatistics(int $resultId): array;
}