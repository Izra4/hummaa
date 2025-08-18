<?php

// app/Repositories/TryoutRepository.php
namespace App\Repositories;

use App\Models\Tryout;
use App\Repositories\Contracts\TryoutRepositoryInterface;
use Illuminate\Support\Collection;

class TryoutRepository implements TryoutRepositoryInterface
{
    public function findById(int $id): ?Tryout
    {
        return Tryout::with(['questions', 'questions.answerChoices'])->find($id);
    }

    public function getPublished(): Collection
    {
        return Tryout::published()
            ->with(['questions'])
            ->withCount('questions')
            ->get();
    }

    public function getByType(string $type): Collection
    {
        return Tryout::published()
            ->byType($type)
            ->with(['questions'])
            ->withCount('questions')
            ->get();
    }

    public function getWithStats(): Collection
    {
        return Tryout::published()
            ->withStats()
            ->get();
    }

    public function create(array $data): Tryout
    {
        return Tryout::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Tryout::where('tryout_id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Tryout::destroy($id);
    }
}
