@csrf
@php $r = $rubrique ?? null; @endphp

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-2">Nom <span class="text-red-400">*</span></label>
    <input type="text" name="name" value="{{ old('name', $r->name ?? '') }}" required
           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
    @error('name')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
    <textarea name="description" rows="3"
              class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">{{ old('description', $r->description ?? '') }}</textarea>
</div>

<div class="grid sm:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Type de contenu <span class="text-red-400">*</span></label>
        <select name="content_type" id="content_type"
                class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
            <option value="oeuvre" @selected(old('content_type', $r->content_type ?? 'oeuvre') === 'oeuvre')>Œuvres (livres / documents PDF)</option>
            <option value="media" @selected(old('content_type', $r->content_type ?? '') === 'media')>Médias (films / séries)</option>
        </select>
    </div>
    <div id="source_filter_wrap">
        <label class="block text-sm font-medium text-gray-300 mb-2">Filtre (médias)</label>
        <select name="source_filter"
                class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
            <option value="">Assignation manuelle (à venir)</option>
            <option value="rare" @selected(old('source_filter', $r->source_filter ?? '') === 'rare')>Avant première (flag « rare »)</option>
        </select>
        <p class="text-gray-500 text-xs mt-1">Utilisé uniquement pour les rubriques de type « Médias ».</p>
    </div>
</div>

<div class="grid sm:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Ordre d'affichage</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $r->sort_order ?? 0) }}"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Image de couverture</label>
        <input type="file" name="cover" accept="image/*"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2.5 text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-primary-500 file:text-white">
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-2">Forfaits qui débloquent cette rubrique</label>
    <p class="text-gray-500 text-xs mb-3">Aucun coché = rubrique publique (accessible à tous). Sinon, réservée aux forfaits cochés.</p>
    <div class="grid sm:grid-cols-2 gap-3">
        @php $selectedPlans = old('plans', isset($r) ? $r->plans->pluck('id')->all() : []); @endphp
        @forelse($plans as $plan)
            <label class="flex items-center gap-3 bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 cursor-pointer hover:border-primary-500">
                <input type="checkbox" name="plans[]" value="{{ $plan->id }}"
                       @checked(in_array($plan->id, $selectedPlans))
                       class="w-4 h-4 accent-primary-500">
                <span class="text-white text-sm">{{ $plan->name }}
                    <span class="text-gray-500">· {{ number_format($plan->price, 0, ',', ' ') }} FCFA</span>
                </span>
            </label>
        @empty
            <p class="text-gray-500 text-sm">Aucun forfait configuré.</p>
        @endforelse
    </div>
</div>

<div class="mb-8">
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $r->is_active ?? true))
               class="w-4 h-4 accent-primary-500">
        <span class="text-white text-sm">Rubrique active (visible dans l'app)</span>
    </label>
</div>

<div class="flex gap-4">
    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition flex-1">
        <i class="fas fa-save mr-2"></i> Enregistrer
    </button>
    <a href="{{ route('rubriques.index') }}" class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition text-center">Annuler</a>
</div>

@push('scripts')
<script>
    // Masque le filtre source quand le type n'est pas « media ».
    (function () {
        const sel = document.getElementById('content_type');
        const wrap = document.getElementById('source_filter_wrap');
        const sync = () => wrap.style.opacity = (sel.value === 'media') ? '1' : '0.4';
        sel.addEventListener('change', sync); sync();
    })();
</script>
@endpush
