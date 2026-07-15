<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningPathCourse extends Model
{
    protected $fillable = [
        'learning_path_id',
        'course_id',
        'order_number',
    ];

    protected $casts = [
        'order_number' => 'integer',
    ];

    public function learningPath(): BelongsTo
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
