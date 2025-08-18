<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\Contracts\FileUploadServiceInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama_depan',
        'nama_belakang',
        'email',
        'no_whatsapp',
        'tanggal_lahir',
        'jenis_kelamin',
        'avatar', 
        'password',
        'provider',
        'provider_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'password' => 'hashed',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get all tryout results for this user
     */
    public function tryoutResults()
    {
        return $this->hasMany(TryoutResult::class);
    }

    /**
     * Get completed tryout results
     */
    public function completedTryouts()
    {
        return $this->hasMany(TryoutResult::class)->where('status', 'selesai');
    }

    /**
     * Get active/in-progress tryout sessions
     */
    public function activeTryouts()
    {
        return $this->hasMany(TryoutResult::class)->where('status', 'sedang dikerjakan');
    }

    /**
     * Get user answers through tryout results
     */
    public function userAnswers()
    {
        return $this->hasManyThrough(UserAnswer::class, TryoutResult::class, 'user_id', 'hasil_id');
    }

    /**
     * Get material progress for this user
     */
    public function materialProgress()
    {
        return $this->belongsToMany(Material::class, 'material_progress', 'user_id', 'material_id')
            ->withPivot('progress_percentage', 'last_accessed_at')
            ->withTimestamps();
    }

    /**
     * Get completed materials
     */
    public function completedMaterials()
    {
        return $this->belongsToMany(Material::class, 'material_progress', 'user_id', 'material_id')
            ->wherePivot('progress_percentage', '>=', 100)
            ->withPivot('progress_percentage', 'last_accessed_at')
            ->withTimestamps();
    }

    // ========== EXISTING METHODS ==========

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        // Fallback to 'name' field if nama_depan/nama_belakang not set
        if ($this->nama_depan || $this->nama_belakang) {
            return trim($this->nama_depan . ' ' . $this->nama_belakang);
        }
        
        return $this->name ?? '';
    }

    /**
     * Get the user's profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): string
    {
        if (app()->bound(FileUploadServiceInterface::class)) {
            $fileUploadService = app(FileUploadServiceInterface::class);
            return $fileUploadService->getProfilePhotoUrl($this->avatar);
        }
        
        // Fallback if service not bound
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('images/default-avatar.png');
    }

    /**
     * Check if user registered via social provider
     */
    public function isSocialUser(): bool
    {
        return !is_null($this->provider);
    }

    /**
     * Check if user registered via Google
     */
    public function isGoogleUser(): bool
    {
        return $this->provider === 'google';
    }

    /**
     * Check if user can change password
     * Social users might not be able to change password
     */
    public function canChangePassword(): bool
    {
        return !$this->isSocialUser();
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): string
    {
        if (!$this->no_whatsapp) {
            return '';
        }

        $phone = $this->no_whatsapp;
        
        // If starts with +62, format it nicely
        if (str_starts_with($phone, '+62')) {
            return '+62 ' . substr($phone, 3);
        }
        
        return $phone;
    }

    // ========== SCOPES ==========

    /**
     * Scope to filter by gender
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('jenis_kelamin', $gender);
    }

    /**
     * Scope to filter verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to get users with tryout statistics
     */
    public function scopeWithTryoutStats($query)
    {
        return $query->withCount([
            'tryoutResults as total_attempts',
            'completedTryouts as completed_attempts'
        ])->withAvg('completedTryouts as average_score', 'skor_akhir')
          ->withMax('completedTryouts as best_score', 'skor_akhir');
    }

    /**
     * Scope to get active users (those who have recent activity)
     */
    public function scopeActive($query, int $days = 7)
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
    }

    // ========== HELPER METHODS ==========

    /**
     * Get user's tryout statistics
     */
    public function getTryoutStats(): array
    {
        return [
            'total_attempts' => $this->tryoutResults()->count(),
            'completed_attempts' => $this->completedTryouts()->count(),
            'in_progress' => $this->activeTryouts()->count(),
            'average_score' => round($this->completedTryouts()->avg('skor_akhir') ?? 0, 2),
            'best_score' => $this->completedTryouts()->max('skor_akhir') ?? 0,
            'total_time_spent' => $this->completedTryouts()->get()->sum(function ($result) {
                return $result->getDurationInMinutes();
            }),
        ];
    }

    /**
     * Get user's material progress statistics
     */
    public function getMaterialStats(): array
    {
        $totalMaterials = Material::count();
        $userProgress = $this->materialProgress()->count();
        $completedMaterials = $this->completedMaterials()->count();
        
        $avgProgress = $this->materialProgress()
            ->avg('material_progress.progress_percentage') ?? 0;

        return [
            'total_materials' => $totalMaterials,
            'accessed_materials' => $userProgress,
            'completed_materials' => $completedMaterials,
            'in_progress' => $userProgress - $completedMaterials,
            'not_started' => $totalMaterials - $userProgress,
            'average_progress' => round($avgProgress, 2),
            'completion_rate' => $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100, 2) : 0,
        ];
    }

    /**
     * Get user's recent tryout history
     */
    public function getRecentTryouts(int $limit = 5)
    {
        return $this->tryoutResults()
            ->with('tryout')
            ->orderBy('waktu_mulai', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's recent material activity
     */
    public function getRecentMaterials(int $limit = 5)
    {
        return $this->materialProgress()
            ->with('category')
            ->orderByPivot('last_accessed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if user has completed a specific tryout
     */
    public function hasCompletedTryout(int $tryoutId): bool
    {
        return $this->completedTryouts()
            ->where('tryout_id', $tryoutId)
            ->exists();
    }

    /**
     * Get user's best score for a specific tryout
     */
    public function getBestScoreForTryout(int $tryoutId): ?float
    {
        return $this->completedTryouts()
            ->where('tryout_id', $tryoutId)
            ->max('skor_akhir');
    }

    /**
     * Check if user has an active tryout session
     */
    public function hasActiveTryout(): bool
    {
        return $this->activeTryouts()->exists();
    }

    /**
     * Get current active tryout session
     */
    public function getActiveTryout(): ?TryoutResult
    {
        return $this->activeTryouts()->first();
    }

    /**
     * Get user's progress for a specific material
     */
    public function getMaterialProgress(int $materialId): ?object
    {
        return $this->materialProgress()
            ->where('material_id', $materialId)
            ->first()?->pivot;
    }

    /**
     * Check if user has completed a specific material
     */
    public function hasCompletedMaterial(int $materialId): bool
    {
        $progress = $this->getMaterialProgress($materialId);
        return $progress && $progress->progress_percentage >= 100;
    }

    /**
     * Get user level based on completed tryouts and average score
     */
    public function getUserLevel(): array
    {
        $stats = $this->getTryoutStats();
        $completed = $stats['completed_attempts'];
        $avgScore = $stats['average_score'];

        $level = match (true) {
            $completed >= 50 && $avgScore >= 85 => ['level' => 'Expert', 'color' => 'purple'],
            $completed >= 25 && $avgScore >= 75 => ['level' => 'Advanced', 'color' => 'blue'],
            $completed >= 10 && $avgScore >= 65 => ['level' => 'Intermediate', 'color' => 'green'],
            $completed >= 5 => ['level' => 'Beginner', 'color' => 'yellow'],
            default => ['level' => 'Newcomer', 'color' => 'gray'],
        };

        return [
            'level' => $level['level'],
            'color' => $level['color'],
            'completed_tryouts' => $completed,
            'average_score' => $avgScore,
        ];
    }

    /**
     * Check if user has specific role (for future role-based features)
     */
    public function hasRole(string $role): bool
    {
        // This is a placeholder for future role-based access control
        // You can implement proper role management later
        return false; // or implement role checking logic
    }

    /**
     * Check if user is admin (placeholder)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}