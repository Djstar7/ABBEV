<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
            'week' => User::where('role', 'user')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => User::where('role', 'user')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load('subscriptions.plan');
        return view('users.show', compact('user'));
    }
}
