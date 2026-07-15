<?php

namespace App\Models;

use App\Concerns\HasObfuscatedRouteKey;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasObfuscatedRouteKey;

    protected $fillable = [
        'name', 'tier', 'description', 'price', 'duration_days', 'features',
        'is_active', 'is_popular', 'order', 'apple_product_id'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /** Rubriques débloquées par ce forfait. */
    public function rubriques()
    {
        return $this->belongsToMany(
            Rubrique::class,
            'plan_rubrique',
            'subscription_plan_id',
            'rubrique_id',
        );
    }
}
