<?php 

namespace App\Helpers;

use App\Models\Material;
use App\Models\User;

class MaterialHelper
{
    public static function getProgressBadge(int $progress): string
    {
        return match (true) {
            $progress >= 100 => 'bg-green-100 text-green-800',
            $progress >= 75 => 'bg-blue-100 text-blue-800',
            $progress >= 50 => 'bg-yellow-100 text-yellow-800',
            $progress >= 25 => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function getProgressText(int $progress): string
    {
        return match (true) {
            $progress >= 100 => 'Completed',
            $progress >= 75 => 'Almost Done',
            $progress >= 50 => 'Half Way',
            $progress >= 25 => 'Getting Started',
            default => 'Not Started',
        };
    }

    public static function estimateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // Average reading speed: 200 words per minute
    }

    public static function getUserMaterialStats(User $user): array
    {
        $progress = $user->materialProgress()->get();

        $completed = $progress->filter(fn($material) => 
            $material->pivot->progress_percentage >= 100
        )->count();

        $inProgress = $progress->filter(fn($material) => 
            $material->pivot->progress_percentage > 0 && 
            $material->pivot->progress_percentage < 100
        )->count();

        $averageProgress = $progress->count() > 0 ? 
            $progress->avg('pivot.progress_percentage') : 0;

        return [
            'total_materials' => $progress->count(),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'not_started' => max(0, Material::count() - $progress->count()),
            'average_progress' => round($averageProgress, 2),
        ];
    }
}