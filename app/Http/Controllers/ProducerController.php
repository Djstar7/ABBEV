<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Gestion des comptes "producteur" (réservée aux admins).
 *
 * À la création, un mot de passe fort est généré automatiquement et affiché
 * UNE seule fois à l'admin (à transmettre au producteur). Le producteur se
 * connecte ensuite sur la même page /admin/login et n'accède qu'à son espace.
 */
class ProducerController extends Controller
{
    public function index()
    {
        $producers = User::where('role', 'producer')
            ->withCount('media')
            ->latest()
            ->paginate(20);

        return view('producers.index', compact('producers'));
    }

    public function create()
    {
        return view('producers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // Mot de passe généré (lettres + chiffres, sans symboles pour faciliter la saisie).
        $password = Str::password(14, true, true, false);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($password),
            'role'              => 'producer',
            'email_verified_at' => now(),
        ]);

        // Identifiants affichés UNE seule fois sur la page liste.
        return redirect()->route('producers.index')->with('new_producer', [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $password,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'producer') {
            return back()->with('error', "Cet utilisateur n'est pas un producteur.");
        }

        $user->delete(); // les contenus du producteur passent user_id = null (nullOnDelete)

        return redirect()->route('producers.index')
            ->with('success', 'Producteur supprimé. Ses contenus restent dans le catalogue (sans propriétaire).');
    }
}
