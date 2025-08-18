<?php 


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'hasil_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tryout_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor_akhir',
        'status',
        'mode', // 'tryout' or 'belajar'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor_akhir' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class, 'tryout_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'hasil_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'sedang dikerjakan');
    }

    public function scopeByMode($query, $mode)
    {
        return $query->where('mode', $mode);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function getDurationInMinutes()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return 0;
        }

        return $this->waktu_mulai->diffInMinutes($this->waktu_selesai);
    }

    public function getRemainingTime()
    {
        if ($this->status === 'selesai' || !$this->waktu_mulai) {
            return 0;
        }

        $elapsed = $this->waktu_mulai->diffInMinutes(now());
        $remaining = $this->tryout->durasi_menit - $elapsed;

        return max(0, $remaining);
    }

    public function isExpired()
    {
        return $this->getRemainingTime() <= 0 && $this->status === 'sedang dikerjakan';
    }

    public function getTotalAnswered()
    {
        return $this->userAnswers()->whereNotNull('pilihan_id')->count();
    }

    public function getCorrectAnswers()
    {
        return $this->userAnswers()
            ->whereHas('choice', function ($q) {
                $q->where('is_correct', true);
            })->count();
    }
}

// app/Models/UserAnswer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $primaryKey = 'jawaban_pengguna_id';
    public $timestamps = false;

    protected $fillable = [
        'hasil_id',
        'soal_id',
        'pilihan_id',
    ];

    // Relationships
    public function result()
    {
        return $this->belongsTo(TryoutResult::class, 'hasil_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'soal_id');
    }

    public function choice()
    {
        return $this->belongsTo(AnswerChoice::class, 'pilihan_id');
    }

    // Scopes
    public function scopeAnswered($query)
    {
        return $query->whereNotNull('pilihan_id');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('pilihan_id');
    }

    public function scopeCorrect($query)
    {
        return $query->whereHas('choice', function ($q) {
            $q->where('is_correct', true);
        });
    }

    public function scopeIncorrect($query)
    {
        return $query->whereHas('choice', function ($q) {
            $q->where('is_correct', false);
        });
    }

    // Helper methods
    public function isCorrect()
    {
        return $this->choice && $this->choice->is_correct;
    }

    public function isAnswered()
    {
        return !is_null($this->pilihan_id);
    }
}