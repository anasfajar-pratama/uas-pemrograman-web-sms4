<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'answer_text',
        'estimated_score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Estimasi skor berdasarkan kesesuaian jawaban dengan kunci.
     * Menggunakan pencocokan kata kunci (keyword matching).
     */
    public function calculateEstimatedScore(): float
    {
        $keywords = $this->question->keywords ?? [];
        if (empty($keywords) || empty($this->answer_text)) {
            return 0;
        }

        $answerLower = mb_strtolower($this->answer_text);
        $matched = 0;

        foreach ($keywords as $kw) {
            if (str_contains($answerLower, mb_strtolower($kw))) {
                $matched++;
            }
        }

        $ratio = $matched / count($keywords);
        $maxPoints = $this->question->points ?? 5;

        // Skala progresif: ≥80% → penuh; 50–79% → 60%; 20–49% → 30%; <20% → 10%
        if ($ratio >= 0.8) {
            return $maxPoints;
        } elseif ($ratio >= 0.5) {
            return round($maxPoints * 0.6, 1);
        } elseif ($ratio >= 0.2) {
            return round($maxPoints * 0.3, 1);
        } elseif ($ratio > 0) {
            return round($maxPoints * 0.1, 1);
        }

        return 0;
    }
}
