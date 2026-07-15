<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StemKit extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'difficulty_level',
        'price',
        'image',
        'components',
        'is_available',
        'stock_quantity',
    ];

    protected $casts = [
        'components' => 'array',
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_stem_kits');
    }
}
