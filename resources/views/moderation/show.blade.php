@extends('admin.layouts.app')

@section('title', 'Examen — ' . $media->title)
@section('header', 'Examen du contenu')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('moderation.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left"></i> Retour à la modération
    </a>

    {{-- Aperçu vidéo --}}
    <div class="bg-dark-100 rounded-xl border border-dark-200 overflow-hidden">
        @php $embed = $media->isMovie() ? $media->bunnyEmbedUrl() : null; @endphp
        @if($embed)
            <div class="aspect-video bg-black">
                <iframe src="{{ $embed }}" class="w-full h-full" allow="autoplay; fullscreen" allowfullscreen></iframe>
            </div>
        @elseif($media->isSeries())
            <div class="p-5">
                <h3 class="text-white font-semibold mb-3"><i class="fas fa-list-ol mr-2 text-gray-400"></i>Épisodes</h3>
                <div class="space-y-3">
                    @forelse($media->seasonsRelation as $season)
                        <p class="text-gray-300 text-sm font-medium">Saison {{ $season->season_number }}</p>
                        @foreach($season->episodes as $ep)
                            @php
                                $epEmbed = ($ep->video_provider === 'bunny' && $ep->video_id)
                                    ? "https://iframe.mediadelivery.net/embed/".($ep->video_library_id ?: config('services.bunny.library_id'))."/".$ep->video_id
                                    : null;
                            @endphp
                            <div class="border border-dark-200 rounded-lg overflow-hidden">
                                <div class="px-3 py-2 text-sm text-white bg-dark-200/40">Ép. {{ $ep->episode_number }} — {{ $ep->title }}</div>
                                @if($epEmbed)
                                    <div class="aspect-video bg-black"><iframe src="{{ $epEmbed }}" class="w-full h-full" allowfullscreen></iframe></div>
                                @else
                                    <p class="px-3 py-2 text-xs text-gray-500">Aperçu indisponible (vidéo locale).</p>
                                @endif
                            </div>
                        @endforeach
                    @empty
                        <p class="text-gray-500 text-sm">Aucun épisode.</p>
                    @endforelse
                </div>
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-film text-3xl mb-2 block opacity-50"></i>
                Aperçu vidéo indisponible (vidéo locale ou non attribuée).
            </div>
        @endif
    </div>

    {{-- Métadonnées --}}
    <div class="bg-dark-100 rounded-xl border border-dark-200 p-5 space-y-2">
        <h2 class="text-xl font-bold text-white">{{ $media->title }}</h2>
        <p class="text-gray-400 text-sm">{{ $media->description ?: 'Aucune description.' }}</p>
        <div class="flex flex-wrap gap-4 text-sm text-gray-400 pt-2">
            <span><i class="fas fa-user mr-1"></i>{{ $media->producer?->name ?? '—' }}</span>
            <span><i class="fas fa-film mr-1"></i>{{ $media->isSeries() ? 'Série' : 'Film' }}</span>
            <span><i class="fas fa-calendar mr-1"></i>{{ $media->release_year ?? '—' }}</span>
            <span><i class="fas fa-folder mr-1"></i>{{ $media->category?->name ?? 'Sans catégorie' }}</span>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        {{-- Approuver : catégorie + tier --}}
        <form method="POST" action="{{ route('moderation.approve', $media->id) }}"
              class="bg-dark-100 rounded-xl border border-green-500/30 p-5 space-y-4"
              data-confirm="Approuver et publier « {{ $media->title }} » ?" data-confirm-type="primary"
              data-confirm-confirm="Approuver">
            @csrf
            <h3 class="text-green-300 font-semibold"><i class="fas fa-circle-check mr-2"></i>Approuver</h3>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Catégorie</label>
                <select name="category_id" required
                        class="w-full bg-dark-50 border border-dark-200 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-primary-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($media->category_id == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Tier (rémunération)</label>
                <select name="tier" required
                        class="w-full bg-dark-50 border border-dark-200 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-primary-500">
                    @foreach($tiers as $t)
                        <option value="{{ $t }}" @selected($media->tier === $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 rounded-lg transition">
                <i class="fas fa-check mr-1"></i> Approuver et publier
            </button>
        </form>

        {{-- Rejeter : motif --}}
        <form method="POST" action="{{ route('moderation.reject', $media->id) }}"
              class="bg-dark-100 rounded-xl border border-red-500/30 p-5 space-y-4"
              data-confirm="Rejeter « {{ $media->title }} » ?" data-confirm-type="danger"
              data-confirm-confirm="Rejeter">
            @csrf
            <h3 class="text-red-300 font-semibold"><i class="fas fa-circle-xmark mr-2"></i>Rejeter</h3>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Motif du rejet</label>
                <textarea name="rejection_reason" rows="4" required
                          placeholder="Expliquez au producteur pourquoi le contenu est rejeté…"
                          class="w-full bg-dark-50 border border-dark-200 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-red-500">{{ $media->rejection_reason }}</textarea>
            </div>
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 rounded-lg transition">
                <i class="fas fa-ban mr-1"></i> Rejeter
            </button>
        </form>
    </div>
</div>
@endsection
