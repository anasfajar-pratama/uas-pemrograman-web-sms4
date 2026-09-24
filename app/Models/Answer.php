<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'answer_text',
        'selected_option',
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

    public function calculateEstimatedScore(): float
    {
        $question = $this->question;
        $maxPoints = $question->points ?? 5;

        if ($question->type === 'pilihan_ganda') {
            return $this->selected_option === $question->answer_key ? $maxPoints : 0;
        }

        // isian & coding: keyword matching
        $keywords = $question->keywords ?? [];
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
