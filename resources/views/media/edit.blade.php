@extends('admin.layouts.app')

@section('title', 'Modifier un média - ABBEV')
@section('header', 'Modifier le média')

@push('styles')
<!-- FilePond CSS -->
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet" />
<style>
    /* Personnalisation FilePond pour le thème sombre */
    .filepond--root {
        font-family: inherit;
    }

    .filepond--drop-label {
        color: #9ca3af !important;
    }

    .filepond--panel-root {
        background-color: #18181b !important;
        border: 2px dashed #27272a !important;
    }

    .filepond--drip {
        background-color: rgba(6, 182, 212, 0.1) !important;
    }

    .filepond--item {
        background-color: #09090b !important;
    }

    .filepond--item-panel {
        background-color: #27272a !important;
    }

    .filepond--file-info-main,
    .filepond--file-info-sub {
        color: #e4e4e7 !important;
    }

    .filepond--file-action-button {
        background-color: rgba(6, 182, 212, 0.2) !important;
    }

    .filepond--file-action-button:hover {
        background-color: rgba(6, 182, 212, 0.3) !important;
    }

    .filepond--file-status-main {
        color: #06b6d4 !important;
    }

    .filepond--file-status-sub {
        color: #9ca3af !important;
    }

    /* Progress bar personnalisée */
    .filepond--file-wrapper {
        border-radius: 0.5rem;
    }

    /* Style pour les images prévisualisées */
    .filepond--image-preview-wrapper {
        background-color: #09090b !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ type: '{{ old('type', $medium->type) }}' }">
    <!-- Header with Icon -->
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Modifier le média</h2>
                <p class="text-gray-400">{{ $medium->title }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('media.update', $medium) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Type & Basic Info Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-primary-400"></i>
                Informations de base
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Type de média <span class="text-primary-400">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="movie" x-model="type" class="peer sr-only" {{ old('type', $medium->type) == 'movie' ? 'checked' : '' }}>
                            <div class="p-4 bg-dark-50 border-2 border-dark-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-500/10 transition-all">
                                <i class="fas fa-film text-2xl text-gray-400 peer-checked:text-primary-400 mb-2"></i>
                                <p class="text-white font-medium">Film</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="series" x-model="type" class="peer sr-only" {{ old('type', $medium->type) == 'series' ? 'checked' : '' }}>
                            <div class="p-4 bg-dark-50 border-2 border-dark-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-500/10 transition-all">
                                <i class="fas fa-tv text-2xl text-gray-400 peer-checked:text-primary-400 mb-2"></i>
                                <p class="text-white font-medium">Série</p>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Catégorie <span class="text-primary-400">*</span>
                    </label>
                    <select name="category_id" id="category_id"
                            class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                            required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $medium->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Titre <span class="text-primary-400">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $medium->title) }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                           placeholder="Ex: Inception, Breaking Bad, One Piece..." required>
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                              placeholder="Synopsis ou résumé du contenu...">{{ old('description', $medium->description) }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Release Year -->
                <div>
                    <label for="release_year" class="block text-sm font-medium text-gray-300 mb-2">
                        Année de sortie
                    </label>
                    <input type="number" name="release_year" id="release_year" value="{{ old('release_year', $medium->release_year ?? date('Y')) }}"
                           min="1900" max="{{ date('Y') + 5 }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                           placeholder="2024">
                    @error('release_year')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration (for movies) -->
                <div x-show="type === 'movie'">
                    <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                        Durée (en minutes)
                    </label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration', $medium->duration ? round($medium->duration / 60) : '') }}"
                           min="1"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                           placeholder="120">
                    <p class="text-gray-400 text-xs mt-1">La durée sera convertie en secondes automatiquement</p>
                    @error('duration')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seasons (for series) -->
                <div x-show="type === 'series'">
                    <label for="seasons" class="block text-sm font-medium text-gray-300 mb-2">
                        Nombre de saisons
                    </label>
                    <input type="number" name="seasons" id="seasons" value="{{ old('seasons', $medium->seasons ?? 1) }}"
                           min="1"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                           placeholder="1">
                    @error('seasons')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Media Files Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-images text-primary-400"></i>
                Fichiers multimédias
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-image mr-1"></i> Vignette (Thumbnail)
                    </label>
                    @if($medium->thumbnail_path)
                        <div class="mb-3 p-3 bg-dark-50 rounded-lg border border-dark-200">
                            <p class="text-gray-400 text-sm mb-2">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $medium->thumbnail_path) }}"
                                 alt="Thumbnail actuelle"
                                 class="h-32 rounded border border-primary-500/20">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
                    <p class="text-gray-400 text-xs mt-1">Image carrée pour la liste (recommandé: 400x400px) - Laisser vide pour conserver</p>
                    @error('thumbnail')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover -->
                <div>
                    <label for="cover" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-portrait mr-1"></i> Couverture (Poster)
                    </label>
                    @if($medium->cover_path)
                        <div class="mb-3 p-3 bg-dark-50 rounded-lg border border-dark-200">
                            <p class="text-gray-400 text-sm mb-2">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $medium->cover_path) }}"
                                 alt="Cover actuelle"
                                 class="h-32 rounded border border-primary-500/20">
                        </div>
                    @endif
                    <input type="file" name="cover" id="cover" accept="image/*">
                    <p class="text-gray-400 text-xs mt-1">Affiche verticale (recommandé: 500x750px) - Laisser vide pour conserver</p>
                    @error('cover')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Banner -->
                <div class="md:col-span-2">
                    <label for="banner" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-panorama mr-1"></i> Bannière (Backdrop)
                    </label>
                    @if($medium->banner_path)
                        <div class="mb-3 p-3 bg-dark-50 rounded-lg border border-dark-200">
                            <p class="text-gray-400 text-sm mb-2">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $medium->banner_path) }}"
                                 alt="Banner actuelle"
                                 class="h-32 rounded border border-primary-500/20">
                        </div>
                    @endif
                    <input type="file" name="banner" id="banner" accept="image/*">
                    <p class="text-gray-400 text-xs mt-1">Image horizontale large (recommandé: 1920x1080px) - Laisser vide pour conserver</p>
                    @error('banner')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Video -->
                <div class="md:col-span-2" x-show="type === 'movie'">
                    <label for="video" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-video mr-1"></i> Fichier vidéo (Film uniquement)
                    </label>
                    @if($medium->video_path)
                        <div class="mb-3 p-3 bg-dark-50 rounded-lg border border-dark-200">
                            <p class="text-gray-400 text-sm">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Vidéo existante : <span class="text-white">{{ basename($medium->video_path) }}</span>
                            </p>
                        </div>
                    @endif
                    <input type="file" name="video" id="video" accept="video/*" data-max-file-size="5GB">
                    <input type="hidden" name="video_path" id="video_path">
                    <p class="text-gray-400 text-xs mt-1">
                        <i class="fas fa-info-circle"></i> Formats acceptés: MP4, MKV, AVI, WEBM (Max: 5GB) - Laisser vide pour conserver
                        <br>
                        <i class="fas fa-rocket"></i> Upload par chunks avec barre de progression et possibilité d'annulation
                    </p>
                    @error('video')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-cog text-primary-400"></i>
                Paramètres
            </h3>

            <div class="space-y-4">
                <!-- Is Featured -->
                <label class="flex items-center gap-3 p-4 bg-dark-50 rounded-lg cursor-pointer hover:bg-dark-200 transition-all">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $medium->is_featured) ? 'checked' : '' }}
                           class="w-5 h-5 text-primary-500 bg-dark-300 border-dark-400 rounded focus:ring-primary-500 focus:ring-2">
                    <div>
                        <span class="text-white font-medium">Mettre en vedette</span>
                        <p class="text-gray-400 text-sm">Ce contenu apparaîtra dans les sections mises en avant</p>
                    </div>
                </label>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-300 mb-2">
                        Date de publication
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at"
                           value="{{ old('published_at', $medium->published_at ? $medium->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all">
                    @error('published_at')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('media.index') }}"
               class="bg-dark-200 hover:bg-dark-300 text-white px-8 py-3 rounded-lg font-medium transition-all duration-300">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 hover:shadow-lg hover:shadow-primary-500/50 text-white px-8 py-3 rounded-lg font-medium transition-all duration-300">
                <i class="fas fa-save mr-2"></i>
                Mettre à jour le média
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- FilePond JavaScript -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>

<script>
    // Enregistrer les plugins FilePond
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
        FilePondPluginFilePoster
    );

    // Configuration CSRF token pour vidéos
    FilePond.setOptions({
        server: {
            url: '{{ route("upload.chunk") }}',
            process: {
                url: '',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                timeout: 0,
                onload: (response) => {
                    const data = JSON.parse(response);
                    document.getElementById('video_path').value = data.path;
                    return data.path;
                },
                onerror: (response) => {
                    console.error('Erreur upload:', response);
                    return response;
                }
            },
            revert: {
                url: '{{ route("upload.delete") }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                onload: () => {
                    document.getElementById('video_path').value = '';
                }
            }
        }
    });

    // Initialiser FilePond pour la vidéo
    const videoInput = document.getElementById('video');
    const videoPond = FilePond.create(videoInput, {
        labelIdle: `
            <div class="filepond-label-content">
                <i class="fas fa-cloud-upload-alt text-4xl text-primary-400 mb-2"></i>
                <p class="text-white font-medium">Glissez-déposez votre vidéo ou <span class="text-primary-400 underline">parcourir</span></p>
                <p class="text-gray-400 text-sm mt-1">Upload par chunks - Annulation possible à tout moment</p>
            </div>
        `,
        acceptedFileTypes: ['video/mp4', 'video/x-matroska', 'video/avi', 'video/webm', 'video/quicktime', 'video/x-msvideo'],
        maxFileSize: '5GB',
        chunkUploads: true,
        chunkSize: 2000000,
        chunkRetryDelays: [500, 1000, 3000],
        allowRevert: true,
        allowRemove: true,
        instantUpload: true,
        credits: false,
        labelFileProcessing: 'Upload en cours',
        labelFileProcessingComplete: 'Upload terminé',
        labelFileProcessingAborted: 'Upload annulé',
        labelFileProcessingError: 'Erreur lors de l\'upload',
        labelTapToCancel: 'Cliquez pour annuler',
        labelTapToRetry: 'Cliquez pour réessayer',
        labelButtonRemoveItem: 'Supprimer',
        labelButtonAbortItemProcessing: 'Annuler',
        labelButtonRetryItemProcessing: 'Réessayer'
    });

    // Initialiser FilePond pour les images
    const thumbnailPond = FilePond.create(document.getElementById('thumbnail'), {
        labelIdle: 'Glissez-déposez votre vignette ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB',
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    const coverPond = FilePond.create(document.getElementById('cover'), {
        labelIdle: 'Glissez-déposez votre couverture ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB',
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    const bannerPond = FilePond.create(document.getElementById('banner'), {
        labelIdle: 'Glissez-déposez votre bannière ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB',
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    // Convert duration to seconds before submit (pour les films)
    document.querySelector('form').addEventListener('submit', function(e) {
        const type = document.querySelector('input[name="type"]:checked').value;
        const durationInput = document.getElementById('duration');

        if (type === 'movie' && durationInput.value) {
            // Convert minutes to seconds
            durationInput.value = parseInt(durationInput.value) * 60;
        }
    });
</script>
@endpush
