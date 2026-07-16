<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rubrique : section thématique dont l'accès dépend des forfaits (pivot
 * plan_rubrique). Deux natures de contenu : 'oeuvre' (documents) ou 'media'
 * (films/séries, ex. contenu rare via source_filter = 'rare').
 */
class Rubrique extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'cover_path',
        'content_type', 'source_filter', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function oeuvres(): HasMany
    {
        return $this->hasMany(Oeuvre::class)->orderBy('sort_order');
    }

    /** Forfaits qui débloquent cette rubrique. */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(
            SubscriptionPlan::class,
            'plan_rubrique',
            'rubrique_id',
            'subscription_plan_id',
        );
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    public function isOeuvre(): bool
    {
        return $this->content_type === 'oeuvre';
    }

    public function isMedia(): bool
    {
        return $this->content_type === 'media';
    }
}
