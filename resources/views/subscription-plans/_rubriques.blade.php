@if(($rubriques ?? collect())->isNotEmpty())
    <div class="mt-6 pt-6 border-t border-dark-200">
        <label class="block text-sm font-medium text-gray-300 mb-1">Rubriques débloquées par ce forfait</label>
        <p class="text-gray-500 text-xs mb-3">L'utilisateur abonné à ce forfait accède aux rubriques cochées.</p>
        @php $selectedRubriques = old('rubriques', isset($plan) ? $plan->rubriques->pluck('id')->all() : []); @endphp
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach($rubriques as $rub)
                <label class="flex items-center gap-3 bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 cursor-pointer hover:border-primary-500">
                    <input type="checkbox" name="rubriques[]" value="{{ $rub->id }}"
                           @checked(in_array($rub->id, $selectedRubriques))
                           class="w-4 h-4 accent-primary-500">
                    <span class="text-white text-sm">{{ $rub->name }}
                        <span class="text-gray-500 text-xs">· {{ $rub->content_type === 'oeuvre' ? 'Œuvres' : 'Médias' }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>
@endif
