<?php 

namespace App\Services;

use App\Models\TryoutResult;
use App\Models\User;
use App\Models\Tryout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatisticsService
{
    public function getUserDashboardStats(User $user): array
    {
        $cacheKey = "user_dashboard_stats_{$user->id}";
        
        return Cache::remember($cacheKey, 1800, function () use ($user) {
            $tryoutStats = [
                'total_attempts' => $user->tryoutResults()->count(),
                'completed_attempts' => $user->tryoutResults()->completed()->count(),
                'average_score' => $user->tryoutResults()->completed()->avg('skor_akhir') ?? 0,
                'best_score' => $user->tryoutResults()->completed()->max('skor_akhir') ?? 0,
            ];

            $materialStats = \App\Helpers\MaterialHelper::getUserMaterialStats($user);

            $recentActivity = $user->tryoutResults()
                ->completed()
                ->with('tryout')
                ->orderBy('waktu_selesai', 'desc')
                ->limit(5)
                ->get();

            return [
                'tryouts' => $tryoutStats,
                'materials' => $materialStats,
                'recent_activity' => $recentActivity,
                'achievements' => $this->getUserAchievements($user),
            ];
        });
    }

    public function getTryoutLeaderboard(int $tryoutId, int $limit = 10): array
    {
        return TryoutResult::where('tryout_id', $tryoutId)
            ->where('status', 'selesai')
            ->with('user')
            ->orderBy('skor_akhir', 'desc')
            ->orderBy('waktu_selesai', 'asc') // Secondary sort by completion time
            ->limit($limit)
            ->get()
            ->map(function ($result, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => $result->user->name,
                    'score' => $result->skor_akhir,
                    'completion_time' => $result->getDurationInMinutes(),
                    'completed_at' => $result->waktu_selesai,
                ];
            })
            ->toArray();
    }

    public function getSystemStats(): array
    {
        $cacheKey = 'system_stats';
        
        return Cache::remember($cacheKey, 3600, function () {
            return [
                'total_users' => User::count(),
                'active_users_today' => User::whereDate('updated_at', today())->count(),
                'total_tryouts' => Tryout::count(),
                'published_tryouts' => Tryout::published()->count(),
                'total_attempts' => TryoutResult::count(),
                'completed_attempts' => TryoutResult::completed()->count(),
                'average_score' => TryoutResult::completed()->avg('skor_akhir') ?? 0,
                'popular_tryouts' => $this->getPopularTryouts(5),
            ];
        });
    }

    private function getUserAchievements(User $user): array
    {
        $achievements = [];
        $completedTryouts = $user->tryoutResults()->completed()->count();
        $bestScore = $user->tryoutResults()->completed()->max('skor_akhir') ?? 0;

        // Attempt-based achievements
        if ($completedTryouts >= 1) $achievements[] = 'First Tryout Complete';
        if ($completedTryouts >= 10) $achievements[] = 'Dedicated Learner';
        if ($completedTryouts >= 50) $achievements[] = 'Tryout Master';

        // Score-based achievements
        if ($bestScore >= 80) $achievements[] = 'High Achiever';
        if ($bestScore >= 95) $achievements[] = 'Nearly Perfect';
        if ($bestScore == 100) $achievements[] = 'Perfect Score';

        // Consistency achievements
        $recentScores = $user->tryoutResults()
            ->completed()
            ->orderBy('waktu_selesai', 'desc')
            ->limit(5)
            ->pluck('skor_akhir');

        if ($recentScores->count() >= 3 && $recentScores->min() >= 70) {
            $achievements[] = 'Consistent Performer';
        }

        return $achievements;
    }

    private function getPopularTryouts(int $limit): array
    {
        return Tryout::published()
            ->withCount('results')
            ->orderBy('results_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($tryout) {
                return [
                    'id' => $tryout->tryout_id,
                    'name' => $tryout->nama_tryout,
                    'type' => $tryout->jenis_tryout,
                    'attempts' => $tryout->results_count,
                ];
            })
            ->toArray();
    }
}
