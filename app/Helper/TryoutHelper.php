<?php 

namespace App\Helpers;

use App\Models\TryoutResult;
use App\Models\User;

class TryoutHelper
{
    public static function calculatePerformanceRating(float $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 80 => 'Very Good',
            $score >= 70 => 'Good',
            $score >= 60 => 'Fair',
            $score >= 50 => 'Poor',
            default => 'Very Poor',
        };
    }

    public static function getScoreColor(float $score): string
    {
        return match (true) {
            $score >= 80 => 'text-green-600',
            $score >= 60 => 'text-yellow-600',
            default => 'text-red-600',
        };
    }

    public static function getUserRank(TryoutResult $result): array
    {
        $tryoutId = $result->tryout_id;
        $userScore = $result->skor_akhir;

        // Get all completed results for this tryout, ordered by score
        $allResults = TryoutResult::where('tryout_id', $tryoutId)
            ->where('status', 'selesai')
            ->orderBy('skor_akhir', 'desc')
            ->get();

        $totalParticipants = $allResults->count();
        $userRank = $allResults->search(function ($item) use ($result) {
            return $item->hasil_id === $result->hasil_id;
        }) + 1;

        $percentile = $totalParticipants > 0 ? 
            round(($totalParticipants - $userRank + 1) / $totalParticipants * 100, 2) : 0;

        return [
            'rank' => $userRank,
            'total_participants' => $totalParticipants,
            'percentile' => $percentile,
            'better_than_percent' => round(($totalParticipants - $userRank) / $totalParticipants * 100, 2)
        ];
    }

    public static function getTimeEfficiency(TryoutResult $result): array
    {
        $totalTime = $result->tryout->durasi_menit;
        $usedTime = $result->getDurationInMinutes();
        
        $efficiency = $totalTime > 0 ? round(($usedTime / $totalTime) * 100, 2) : 0;
        
        return [
            'total_time' => $totalTime,
            'used_time' => $usedTime,
            'remaining_time' => max(0, $totalTime - $usedTime),
            'efficiency_percentage' => $efficiency,
            'time_per_question' => $result->getTotalAnswered() > 0 ? 
                round($usedTime / $result->getTotalAnswered(), 2) : 0,
        ];
    }

    public static function generateRecommendations(TryoutResult $result): array
    {
        $score = $result->skor_akhir;
        $stats = [
            'correct' => $result->getCorrectAnswers(),
            'total' => $result->tryout->getTotalQuestions(),
        ];

        $recommendations = [];

        if ($score < 60) {
            $recommendations[] = 'Focus on fundamental concepts and practice more basic questions.';
            $recommendations[] = 'Consider reviewing study materials before attempting more tryouts.';
        } elseif ($score < 80) {
            $recommendations[] = 'Good progress! Work on time management and accuracy.';
            $recommendations[] = 'Practice more complex problems to improve your score.';
        } else {
            $recommendations[] = 'Excellent performance! Keep up the consistent practice.';
            $recommendations[] = 'Try more challenging tryouts to further enhance your skills.';
        }

        // Time-based recommendations
        $timeEfficiency = self::getTimeEfficiency($result);
        if ($timeEfficiency['efficiency_percentage'] > 90) {
            $recommendations[] = 'Consider spending more time reviewing your answers.';
        } elseif ($timeEfficiency['efficiency_percentage'] < 50) {
            $recommendations[] = 'Good time management! You had time to spare.';
        }

        return $recommendations;
    }
}