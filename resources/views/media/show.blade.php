@extends('admin.layouts.app')

@section('title', $medium->title . ' - ABBEV')
@section('header', $medium->title)

@section('content')
<div class="space-y-6">
    <!-- Hero Banner -->
    @if($medium->banner_path)
    <div class="relative w-full h-96 rounded-xl overflow-hidden shadow-2xl">
        <img src="{{ str_starts_with($medium->banner_path, 'http') ? $medium->banner_path : asset('storage/' . $medium->banner_path) }}"
             alt="{{ $medium->title }}"
             class="w-full h-full object-cover"
             loading="lazy">

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-dark-bg via-dark-bg/60 to-transparent"></div>

        <!-- Quick Info -->
        <div class="absolute bottom-6 left-6 right-6">
            <div class="flex items-center gap-3 mb-3">
                @if($medium->is_featured)
                    <span class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm px-4 py-1.5 rounded-full font-medium shadow-lg">
                        <i class="fas fa-star"></i> En Vedette
                    </span>
                @endif
                <span class="bg-primary-500 text-white text-sm px-4 py-1.5 rounded-full font-medium shadow-lg">
                    {{ $medium->type === 'movie' ? 'Film' : 'Série' }}
                </span>
                @if($medium->release_year)
                    <span class="bg-dark-100/80 backdrop-blur-sm text-white text-sm px-4 py-1.5 rounded-full font-medium">
                        {{ $medium->release_year }}
                    </span>
                @endif
            </div>

            <h1 class="text-4xl font-bold text-white mb-2">{{ $medium->title }}</h1>

            <div class="flex items-center gap-4 text-gray-300">
                <span class="flex items-center gap-2">
                    <i class="fas fa-folder text-primary-400"></i>
                    {{ $medium->category->name ?? 'Non catégorisé' }}
                </span>
                @if($medium->duration)
                    <span>•</span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-clock text-primary-400"></i>
                        {{ gmdate('H:i:s', $medium->duration) }}
                    </span>
                @endif
                @if($medium->seasons)
                    <span>•</span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-list text-primary-400"></i>
                        {{ $medium->seasons }} {{ $medium->seasons > 1 ? 'Saisons' : 'Saison' }}
                    </span>
                @endif
                <span>•</span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-eye text-primary-400"></i>
                    {{ number_format($medium->views_count ?? 0) }} vues
                </span>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Video Player -->
            @if($medium->video_path)
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
                <div class="aspect-video bg-black">
                    @if(str_starts_with($medium->video_path, 'http'))
                        <div class="w-full h-full flex items-center justify-center text-white">
                            <div class="text-center">
                                <i class="fas fa-video text-6xl mb-4 text-primary-400"></i>
                                <p class="text-xl">Vidéo disponible en ligne</p>
                                <a href="{{ $medium->video_path }}" target="_blank"
                                   class="inline-block mt-4 bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition">
                                    Regarder maintenant
                                </a>
                            </div>
                        </div>
                    @else
                        <video controls class="w-full h-full" poster="{{ $medium->thumbnail_path ? asset('storage/' . $medium->thumbnail_path) : '' }}">
                            <source src="{{ asset('storage/' . $medium->video_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-8">
                <div class="text-center text-gray-400">
                    <i class="fas fa-video-slash text-6xl mb-4"></i>
                    <p class="text-xl">Aucune vidéo disponible pour ce contenu</p>
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($medium->description)
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
                <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-primary-400"></i>
                    Synopsis
                </h2>
                <p class="text-gray-300 leading-relaxed text-lg">{{ $medium->description }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Poster -->
            @if($medium->cover_path || $medium->thumbnail_path)
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
                <img src="{{ str_starts_with($medium->cover_path ?? $medium->thumbnail_path, 'http') ? ($medium->cover_path ?? $medium->thumbnail_path) : asset('storage/' . ($medium->cover_path ?? $medium->thumbnail_path)) }}"
                     alt="{{ $medium->title }}"
                     class="w-full h-auto"
                     loading="lazy">
            </div>
            @endif

            <!-- Details -->
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-primary-400"></i>
                    Détails
                </h2>

                <div class="space-y-3">
                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Type</span>
                        <span class="text-white font-medium">{{ $medium->type === 'movie' ? 'Film' : 'Série' }}</span>
                    </div>

                    @if($medium->release_year)
                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Année</span>
                        <span class="text-white font-medium">{{ $medium->release_year }}</span>
                    </div>
                    @endif

                    @if($medium->duration)
                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Durée</span>
                        <span class="text-white font-medium">{{ gmdate('H:i:s', $medium->duration) }}</span>
                    </div>
                    @endif

                    @if($medium->seasons)
                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Saisons</span>
                        <span class="text-white font-medium">{{ $medium->seasons }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Catégorie</span>
                        <span class="text-primary-400 font-medium">{{ $medium->category->name ?? 'Non catégorisé' }}</span>
                    </div>

                    <div class="flex justify-between items-start pb-3 border-b border-dark-200">
                        <span class="text-gray-400">Vues</span>
                        <span class="text-white font-medium">{{ number_format($medium->views_count ?? 0) }}</span>
                    </div>

                    @if($medium->published_at)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400">Publié le</span>
                        <span class="text-white font-medium">{{ $medium->published_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-primary-400"></i>
                    Actions
                </h2>

                <div class="space-y-3">
                    <a href="{{ route('media.edit', $medium) }}"
                       class="w-full bg-primary-500 hover:bg-primary-600 text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>

                    <a href="{{ route('media.index') }}"
                       class="w-full bg-dark-200 hover:bg-dark-300 text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste
                    </a>

                    <form action="{{ route('media.destroy', $medium) }}" method="POST"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
