<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'soal_id';

    protected $fillable = [
        'isi_soal',
        'pembahasan',
        'image_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function answerChoices()
    {
        return $this->hasMany(AnswerChoice::class, 'soal_id');
    }

    public function correctAnswer()
    {
        return $this->hasOne(AnswerChoice::class, 'soal_id')
            ->where('is_correct', true);
    }

    public function tryouts()
    {
        return $this->belongsToMany(Tryout::class, 'tryout_question', 'soal_id', 'tryout_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'soal_id');
    }

    // Scopes
    public function scopeWithChoices($query)
    {
        return $query->with('answerChoices');
    }

    public function scopeForTryout($query, $tryoutId)
    {
        return $query->whereHas('tryouts', function ($q) use ($tryoutId) {
            $q->where('tryout_id', $tryoutId);
        });
    }

    // Helper methods
    public function getCorrectChoiceId()
    {
        return $this->correctAnswer?->pilihan_id;
    }

    public function isCorrectAnswer($choiceId)
    {
        return $this->answerChoices()
            ->where('pilihan_id', $choiceId)
            ->where('is_correct', true)
            ->exists();
    }

    public function hasImage()
    {
        return !empty($this->image_path);
    }
}