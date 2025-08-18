<?php 

namespace App\Models;

use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'materi_id';

    protected $fillable = [
        'kategori_id',
        'judul',
        'isi_materi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'kategori_id');
    }

    public function userProgress()
    {
        return $this->belongsToMany(User::class, 'material_progress')
            ->withPivot('progress_percentage', 'last_accessed_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('kategori_id', $categoryId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getUserProgress($userId)
    {
        return $this->userProgress()
            ->where('user_id', $userId)
            ->first()?->pivot;
    }

    public function isCompletedBy($userId)
    {
        $progress = $this->getUserProgress($userId);
        return $progress && $progress->progress_percentage >= 100;
    }
}