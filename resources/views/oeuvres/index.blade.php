@extends('admin.layouts.app')

@section('title', 'Œuvres - ABBEV')
@section('header', 'Œuvres (livres / documents)')

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('rubriques.index') }}" class="inline-flex items-center text-primary-400 hover:text-primary-300">
        <i class="fas fa-arrow-left mr-2"></i> Rubriques
    </a>
    <a href="{{ route('oeuvres.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i> Nouvelle œuvre
    </a>
</div>

<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark-200/40 text-gray-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Titre</th>
                    <th class="text-left px-4 py-3">Auteur</th>
                    <th class="text-left px-4 py-3">Rubrique</th>
                    <th class="text-center px-4 py-3">PDF</th>
                    <th class="text-center px-4 py-3">Actif</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-200/70">
                @forelse($oeuvres as $o)
                    <tr class="hover:bg-dark-200/30">
                        <td class="px-5 py-3 text-white font-medium">{{ $o->title }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ $o->author ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ $o->rubrique?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($o->file_path)<i class="fas fa-file-pdf text-red-400"></i>@else<span class="text-gray-600">—</span>@endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($o->is_active)<i class="fas fa-check-circle text-green-400"></i>@else<i class="fas fa-circle text-gray-600"></i>@endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('oeuvres.edit', $o) }}" class="bg-primary-500/20 hover:bg-primary-500 text-primary-400 hover:text-white px-3 py-2 rounded-lg transition"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('oeuvres.destroy', $o) }}" method="POST"
                                      data-confirm="Supprimer l'œuvre « {{ $o->title }} » ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-2 rounded-lg transition"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Aucune œuvre. Créez d'abord une rubrique de type « Œuvres ».</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $oeuvres->links() }}</div>
@endsection
