<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Services\KpayService;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = Configuration::all()->groupBy('group');

        return view('configuration.index', compact('configurations'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*' => 'nullable|string',
        ]);

        foreach ($validated['configs'] as $key => $value) {
            Configuration::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'Configuration mise à jour avec succès.');
    }

    /**
     * Sauvegarder uniquement les paramètres d'un groupe (onglet),
     * sans toucher aux autres catégories de configuration.
     */
    public function updateGroup(Request $request, string $group)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*' => 'nullable|string',
        ]);

        // On ne met à jour que les clés appartenant réellement au
        // groupe ciblé (sécurité : ignore toute clé hors groupe).
        $allowedKeys = Configuration::where('group', $group)->pluck('key')->all();

        $updated = 0;
        foreach ($validated['configs'] as $key => $value) {
            if (in_array($key, $allowedKeys, true)) {
                Configuration::where('key', $key)->update(['value' => $value]);
                $updated++;
            }
        }

        return back()
            ->with('success', "Configuration « {$group} » mise à jour ({$updated} paramètre(s)).")
            ->with('active_tab', $group);
    }

    /**
     * Teste la connectivité avec l'API KPay en vérifiant les credentials.
     */
    public function testKpay(KpayService $kpay)
    {
        if (!$kpay->isConfigured()) {
            return back()
                ->with('error', 'KPay non configuré : veuillez renseigner l\'URL, l\'API Key et la Secret Key.')
                ->with('active_tab', 'kpay');
        }

        // On vérifie les credentials en récupérant un paiement fictif.
        // Credentials valides → 404 (not found), invalides → 401.
        // Aucun effet de bord (pas de vrai paiement initié).
        $result = $kpay->getPayment('test-connectivity-' . time());

        $http = $result['http'] ?? null;

        // 2xx ou 404 = credentials acceptés (le paiement fictif n'existe pas, c'est normal)
        if ($result['success'] || $http === 404) {
            return back()
                ->with('success', 'Connexion KPay réussie ! Les credentials sont valides.')
                ->with('active_tab', 'kpay');
        }

        $message = $result['message'] ?? 'Erreur inconnue';

        return back()
            ->with('error', "Échec de connexion KPay (HTTP {$http}) : {$message}")
            ->with('active_tab', 'kpay');
    }
}
