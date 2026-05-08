<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('external_reference', 'like', "%{$search}%")
                  ->orWhere('payer_email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20);

        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'revenue' => Transaction::where('status', 'completed')->sum('net_amount'),
        ];

        return view('transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user');
        return view('transactions.show', compact('transaction'));
    }
}
