<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningMaterial extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'file_path',
        'file_type',
        'file_size_kb',
        'download_count',
    ];

    protected $casts = [
        'file_size_kb' => 'integer',
        'download_count' => 'integer',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
