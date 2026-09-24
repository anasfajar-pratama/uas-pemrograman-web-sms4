<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'last_active_at',
        'elapsed_seconds',
        'finished_at',
        'expected_grade',
        'grade_reason',
        'estimated_grade',
    ];

    protected function casts(): array
    {
        return [
            'started_at'     => 'datetime',
            'last_active_at' => 'datetime',
            'finished_at'    => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function getExamDurationSecondsAttribute(): int
    {
        return $this->exam ? $this->exam->duration_minutes * 60 : 7200;
    }

    public function getRemainingSecondsAttribute(): int
    {
        $remaining = $this->exam_duration_seconds - $this->elapsed_seconds;
        return max(0, $remaining);
    }

    public function isTimeUp(): bool
    {
        return $this->elapsed_seconds >= $this->exam_duration_seconds;
    }

    public function isFinished(): bool
    {
        return $this->finished_at !== null;
    }
}
