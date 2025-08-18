<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    use HasFactory;

    protected $primaryKey = 'tryout_id';

    protected $fillable = [
        'nama_tryout',
        'jenis_tryout',
        'deskripsi',
        'durasi_menit',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'tryout_question', 'tryout_id', 'soal_id');
    }

    public function results()
    {
        return $this->hasMany(TryoutResult::class, 'tryout_id');
    }

    public function completedResults()
    {
        return $this->hasMany(TryoutResult::class, 'tryout_id')
            ->where('status', 'selesai');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('jenis_tryout', $type);
    }

    public function scopeWithStats($query)
    {
        return $query->withCount([
            'results as total_attempts',
            'completedResults as completed_attempts'
        ])->with(['completedResults' => function ($q) {
            $q->selectRaw('tryout_id, AVG(skor_akhir) as avg_score, MAX(skor_akhir) as max_score')
                ->groupBy('tryout_id');
        }]);
    }

    // Helper methods
    public function getTotalQuestions()
    {
        return $this->questions()->count();
    }

    public function getAverageScore()
    {
        return $this->completedResults()->avg('skor_akhir');
    }

    public function getRandomizedQuestions()
    {
        return $this->questions()->inRandomOrder()->get();
    }
}