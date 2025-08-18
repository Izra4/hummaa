<?php

// app/Repositories/Contracts/QuestionRepositoryInterface.php
namespace App\Repositories\Contracts;

use App\Models\Question;
use Illuminate\Support\Collection;

interface QuestionRepositoryInterface
{
    /**
     * Find question by ID
     */
    public function findById(int $id): ?Question;

    /**
     * Get questions for tryout with optional randomization
     */
    public function getForTryout(int $tryoutId, bool $randomize = false): Collection;

    /**
     * Get question with answer choices
     */
    public function getWithChoices(int $id): ?Question;

    /**
     * Search questions by query
     */
    public function search(string $query): Collection;

    /**
     * Create new question
     */
    public function create(array $data): Question;

    /**
     * Update question by ID
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete question by ID
     */
    public function delete(int $id): bool;

    /**
     * Attach question to tryout
     */
    public function attachToTryout(int $questionId, int $tryoutId): bool;

    /**
     * Detach question from tryout
     */
    public function detachFromTryout(int $questionId, int $tryoutId): bool;
}