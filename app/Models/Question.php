<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'number',
        'section',
        'type',
        'question_text',
        'answer_key',
        'options',
        'keywords',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'options'  => 'array',
            'keywords' => 'array',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getSectionLabelAttribute(): string
    {
        return match ($this->section) {
            'teori'  => 'Teori',
            'logika' => 'Logika',
            'coding' => 'Coding',
            default  => ucfirst($this->section),
        };
    }

    public function getSectionColorAttribute(): string
    {
        return match ($this->section) {
            'teori'  => 'blue',
            'logika' => 'yellow',
            'coding' => 'green',
            default  => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'pilihan_ganda' => 'Pilihan Ganda',
            'isian'         => 'Isian',
            'coding'        => 'Coding',
            default         => ucfirst($this->type),
        };
    }

    public function getOptionsListAttribute(): array
    {
        if (empty($this->options)) {
            return [];
        }

        return $this->options;
    }
}
