<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'code',
        'duration_minutes',
        'is_active',
        'created_by',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        if (!$this->is_active) {
            return true;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return true;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function getDurationSecondsAttribute(): int
    {
        return $this->duration_minutes * 60;
    }

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'Berakhir';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'Belum Dimulai';
        }

        return 'Aktif';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_label) {
            'Aktif'         => 'green',
            'Nonaktif'      => 'red',
            'Berakhir'      => 'gray',
            'Belum Dimulai' => 'yellow',
            default         => 'gray',
        };
    }

    public static function generateCode(): string
    {
        do {
            $code = 'UJIAN-' . strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
