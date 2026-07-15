<?php

namespace App\Services;

use App\Models\Rubrique;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Collection;

/**
 * Décide de l'accès aux rubriques selon le forfait de l'utilisateur.
 *
 * Règle : une rubrique est accessible si elle n'est liée à AUCUN forfait
 * (rubrique publique) OU si l'utilisateur possède un abonnement actif dont le
 * forfait figure parmi ceux qui débloquent la rubrique (pivot plan_rubrique).
 */
class RubriqueAccessService
{
    /** Ids des forfaits des abonnements actifs de l'utilisateur. */
    public function userActivePlanIds(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        return UserSubscription::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->pluck('subscription_plan_id')
            ->unique()
            ->values();
    }

    public function canAccess(?User $user, Rubrique $rubrique): bool
    {
        $planIds = $rubrique->relationLoaded('plans')
            ? $rubrique->plans->pluck('id')
            : $rubrique->plans()->pluck('subscription_plans.id');

        if ($planIds->isEmpty()) {
            return true; // rubrique publique (aucun forfait requis)
        }

        return $this->userActivePlanIds($user)
            ->intersect($planIds)
            ->isNotEmpty();
    }

    /**
     * Rubriques ACTIVES accessibles à l'utilisateur (masque celles verrouillées).
     *
     * @return Collection<int,Rubrique>
     */
    public function accessibleRubriques(?User $user): Collection
    {
        return Rubrique::query()->active()->ordered()->with('plans:id')->get()
            ->filter(fn (Rubrique $r) => $this->canAccess($user, $r))
            ->values();
    }
}
