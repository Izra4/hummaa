<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\TryoutHelper;

class TryoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->tryout_id,
            'name' => $this->nama_tryout,
            'type' => $this->jenis_tryout,
            'description' => $this->deskripsi,
            'duration' => $this->durasi_menit,
            'question_count' => $this->questions_count ?? $this->getTotalQuestions(),
            'is_published' => $this->is_published,
            'average_score' => round($this->getAverageScore() ?? 0, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}