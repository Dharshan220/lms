<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LearningPath extends Model
{
    protected $fillable = [
        'title',
        'description',
        'level',
        'icon',
        'color',
        'order_number',
        'is_active',
    ];

    protected $casts = [
        'order_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses')
            ->withPivot('order_number')
            ->orderByPivot('order_number')
            ->withTimestamps();
    }
}
