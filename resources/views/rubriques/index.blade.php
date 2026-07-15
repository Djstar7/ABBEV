@extends('admin.layouts.app')

@section('title', 'Rubriques - ABBEV')
@section('header', 'Rubriques')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-400 text-sm">Sections thématiques dont l'accès est débloqué par les forfaits d'abonnement.</p>
    <div class="flex gap-3">
        <a href="{{ route('oeuvres.index') }}" class="bg-dark-200 hover:bg-dark-300 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-book-open mr-2"></i> Œuvres
        </a>
        <a href="{{ route('rubriques.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Nouvelle rubrique
        </a>
    </div>
</div>

<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark-200/40 text-gray-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Rubrique</th>
                    <th class="text-left px-4 py-3">Type</th>
                    <th class="text-left px-4 py-3">Contenu</th>
                    <th class="text-left px-4 py-3">Forfaits</th>
                    <th class="text-center px-4 py-3">Actif</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-200/70">
                @forelse($rubriques as $r)
                    <tr class="hover:bg-dark-200/30">
                        <td class="px-5 py-3">
                            <div class="text-white font-medium">{{ $r->name }}</div>
                            <div class="text-gray-500 text-xs">{{ Str::limit($r->description, 60) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($r->content_type === 'oeuvre')
                                <span class="px-2 py-1 rounded-lg bg-primary-500/15 text-primary-300 text-xs"><i class="fas fa-book mr-1"></i>Œuvres</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-cyan-500/15 text-cyan-300 text-xs"><i class="fas fa-film mr-1"></i>Médias{{ $r->source_filter === 'rare' ? ' · rares' : '' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $r->content_type === 'oeuvre' ? $r->oeuvres_count . ' œuvre(s)' : '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            @if($r->plans->isEmpty())
                                <span class="text-gray-500 text-xs italic">Public (tous)</span>
                            @else
                                {{ $r->plans->pluck('name')->join(', ') }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($r->is_active)
                                <i class="fas fa-check-circle text-green-400"></i>
                            @else
                                <i class="fas fa-circle text-gray-600"></i>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('rubriques.edit', $r) }}" title="Modifier"
                                   class="bg-primary-500/20 hover:bg-primary-500 text-primary-400 hover:text-white px-3 py-2 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('rubriques.destroy', $r) }}" method="POST"
                                      data-confirm="Supprimer la rubrique « {{ $r->name }} » ? Les œuvres associées seront aussi supprimées."
                                      data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Supprimer"
                                            class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-2 rounded-lg transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Aucune rubrique.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
