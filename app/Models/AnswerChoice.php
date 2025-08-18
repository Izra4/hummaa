<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'pilihan_id';
    public $timestamps = false;

    protected $fillable = [
        'soal_id',
        'isi_pilihan',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(Question::class, 'soal_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'pilihan_id');
    }

    // Scopes
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }
}