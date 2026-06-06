<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id',
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

    public const EXAM_DURATION = 7200; // 120 menit dalam detik

    /**
     * Sisa waktu dalam detik.
     */
    public function getRemainingSecondsAttribute(): int
    {
        $remaining = self::EXAM_DURATION - $this->elapsed_seconds;
        return max(0, $remaining);
    }

    /**
     * Apakah waktu ujian sudah habis?
     */
    public function isTimeUp(): bool
    {
        return $this->elapsed_seconds >= self::EXAM_DURATION;
    }

    /**
     * Apakah ujian sudah selesai (submit)?
     */
    public function isFinished(): bool
    {
        return $this->finished_at !== null;
    }
}
