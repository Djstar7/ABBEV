@extends('admin.layouts.app')

@section('title', 'Transactions - ABBEV')
@section('header', 'Historique des Transactions')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Total</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="w-12 h-12 bg-primary-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-xl text-primary-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Complétées</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['completed']) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-xl text-green-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">En attente</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-xl text-yellow-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400">Revenu total</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['revenue']) }} XAF</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-xl text-purple-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-4 mb-6">
    <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="ID, email, référence..."
                   class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
        </div>

        <!-- Status Filter -->
        <div>
            <select name="status"
                    class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <option value="">Tous les statuts</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complété</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échoué</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <!-- Payment Method Filter -->
        <div>
            <select name="payment_method"
                    class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <option value="">Toutes les méthodes</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Espèces</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Carte</option>
                <option value="mobile" {{ request('payment_method') === 'mobile' ? 'selected' : '' }}>Mobile Money</option>
                <option value="paypal" {{ request('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="freemopay" {{ request('payment_method') === 'freemopay' ? 'selected' : '' }}>FreeMoPay</option>
                <option value="fedapay" {{ request('payment_method') === 'fedapay' ? 'selected' : '' }}>FedaPay</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i> Filtrer
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_method']))
            <a href="{{ route('transactions.index') }}"
               class="bg-dark-200 hover:bg-dark-300 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-dark-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">ID Transaction</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Utilisateur</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Montant</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Méthode</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-200">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-dark-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm text-white">{{ $transaction->transaction_id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-2">
                                <p class="text-white text-sm">{{ $transaction->user->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $transaction->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-white font-medium">{{ number_format($transaction->amount) }} {{ $transaction->currency }}</p>
                            @if($transaction->fees > 0)
                            <p class="text-gray-400 text-xs">Frais: {{ number_format($transaction->fees) }} {{ $transaction->currency }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-300 capitalize text-sm">
                            @if($transaction->payment_method === 'mobile')
                            <i class="fas fa-mobile-alt mr-1"></i>
                            @elseif($transaction->payment_method === 'card')
                            <i class="fas fa-credit-card mr-1"></i>
                            @elseif($transaction->payment_method === 'paypal')
                            <i class="fab fa-paypal mr-1"></i>
                            @elseif($transaction->payment_method === 'cash')
                            <i class="fas fa-money-bill mr-1"></i>
                            @else
                            <i class="fas fa-wallet mr-1"></i>
                            @endif
                            {{ $transaction->payment_method }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-300 text-sm">{{ $transaction->created_at->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-xs">{{ $transaction->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($transaction->status === 'completed')
                        <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Complété
                        </span>
                        @elseif($transaction->status === 'pending')
                        <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-clock mr-1"></i> En attente
                        </span>
                        @elseif($transaction->status === 'failed')
                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-times-circle mr-1"></i> Échoué
                        </span>
                        @else
                        <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-ban mr-1"></i> Annulé
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('transactions.show', $transaction) }}"
                           class="bg-primary-500/20 hover:bg-primary-500 text-primary-400 hover:text-white px-3 py-2 rounded-lg text-sm transition">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-receipt text-4xl mb-3"></i>
                            <p>Aucune transaction trouvée</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-dark-200">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
