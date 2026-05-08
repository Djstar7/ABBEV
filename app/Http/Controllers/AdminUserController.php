<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->paginate(20);
        $stats = [
            'total' => User::where('role', 'admin')->count(),
        ];

        return view('administrators.index', compact('admins', 'stats'));
    }

    public function create()
    {
        return view('administrators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';

        User::create($validated);

        return redirect()->route('administrators.index')
            ->with('success', 'Administrateur ajouté avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'Cet utilisateur n\'est pas un administrateur.');
        }

        $user->delete();

        return redirect()->route('administrators.index')
            ->with('success', 'Administrateur supprimé avec succès.');
    }
}
