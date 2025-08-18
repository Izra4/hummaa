<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\TryoutHelper;

class TryoutResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $ranking = TryoutHelper::getUserRank($this->resource);
        $timeEfficiency = TryoutHelper::getTimeEfficiency($this->resource);
        
        return [
            'id' => $this->hasil_id,
            'tryout' => new TryoutResource($this->whenLoaded('tryout')),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
            'score' => $this->skor_akhir,
            'performance_rating' => TryoutHelper::calculatePerformanceRating($this->skor_akhir),
            'status' => $this->status,
            'mode' => $this->mode,
            'started_at' => $this->waktu_mulai,
            'completed_at' => $this->waktu_selesai,
            'duration_minutes' => $this->getDurationInMinutes(),
            'total_questions' => $this->tryout->getTotalQuestions(),
            'answered_questions' => $this->getTotalAnswered(),
            'correct_answers' => $this->getCorrectAnswers(),
            'ranking' => $ranking,
            'time_efficiency' => $timeEfficiency,
            'recommendations' => TryoutHelper::generateRecommendations($this->resource),
        ];
    }
}