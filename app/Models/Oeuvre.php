<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Œuvre : contenu de type livre/document (PDF) rattaché à une rubrique.
 */
class Oeuvre extends Model
{
    protected $table = 'oeuvres';

    protected $fillable = [
        'rubrique_id', 'title', 'slug', 'description', 'author',
        'cover_path', 'file_path', 'pages', 'sort_order', 'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'pages' => 'integer',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    public function rubrique(): BelongsTo
    {
        return $this->belongsTo(Rubrique::class);
    }

    /** Œuvres publiées (actives et published_at nul ou passé). */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_active', true)
            ->where(function ($sub) {
                $sub->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
