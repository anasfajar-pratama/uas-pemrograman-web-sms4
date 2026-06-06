<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'number',
        'section',
        'question_text',
        'answer_key',
        'keywords',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
        ];
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
}
