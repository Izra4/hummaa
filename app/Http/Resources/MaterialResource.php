<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\MaterialHelper;

class MaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userProgress = $this->getUserProgress(auth()->id());
        
        return [
            'id' => $this->materi_id,
            'title' => $this->judul,
            'content' => $this->isi_materi,
            'category' => [
                'id' => $this->category->kategori_id,
                'name' => $this->category->nama_kategori,
            ],
            'estimated_reading_time' => MaterialHelper::estimateReadingTime($this->isi_materi),
            'user_progress' => $userProgress ? [
                'percentage' => $userProgress->progress_percentage,
                'last_accessed' => $userProgress->last_accessed_at,
                'is_completed' => $userProgress->progress_percentage >= 100,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}