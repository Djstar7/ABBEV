<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $fillable = [
        'category_id',
        'type',
        'title',
        'slug',
        'description',
        'duration',
        'release_year',
        'seasons',
        'video_path',
        'thumbnail_path',
        'banner_path',
        'cover_path',
        'published_at',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Une série a plusieurs saisons.
     */
    public function seasonsRelation(): HasMany
    {
        return $this->hasMany(Season::class)->orderBy('season_number');
    }

    /**
     * Vérifier si c'est une série.
     */
    public function isSeries(): bool
    {
        return $this->type === 'series';
    }

    /**
     * Vérifier si c'est un film.
     */
    public function isMovie(): bool
    {
        return $this->type === 'movie';
    }
}
