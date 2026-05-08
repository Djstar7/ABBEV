@extends('admin.layouts.app')

@section('title', 'Ajouter un média - ABBEV')
@section('header', 'Ajouter un nouveau média')

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
<div class="max-w-5xl mx-auto" x-data="{ type: '{{ old('type', 'movie') }}' }">
    <!-- Header with Icon -->
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Nouveau média</h2>
                <p class="text-gray-400">Ajoutez un film, une série ou un anime à votre catalogue</p>
            </div>
        </div>
    </div>

    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                            <input type="radio" name="type" value="movie" x-model="type" class="peer sr-only" {{ old('type', 'movie') == 'movie' ? 'checked' : '' }}>
                            <div class="p-4 bg-dark-50 border-2 border-dark-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-500/10 transition-all">
                                <i class="fas fa-film text-2xl text-gray-400 peer-checked:text-primary-400 mb-2"></i>
                                <p class="text-white font-medium">Film</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="series" x-model="type" class="peer sr-only" {{ old('type') == 'series' ? 'checked' : '' }}>
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
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
                              placeholder="Synopsis ou résumé du contenu...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Release Year -->
                <div>
                    <label for="release_year" class="block text-sm font-medium text-gray-300 mb-2">
                        Année de sortie
                    </label>
                    <input type="number" name="release_year" id="release_year" value="{{ old('release_year', date('Y')) }}"
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
                    <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
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
                    <input type="number" name="seasons" id="seasons" value="{{ old('seasons', 1) }}"
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
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-primary-500 file:to-primary-600 file:text-white hover:file:shadow-lg hover:file:shadow-primary-500/50 file:transition-all">
                    <p class="text-gray-400 text-xs mt-1">Image carrée pour la liste (recommandé: 400x400px)</p>
                    @error('thumbnail')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover -->
                <div>
                    <label for="cover" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-portrait mr-1"></i> Couverture (Poster)
                    </label>
                    <input type="file" name="cover" id="cover" accept="image/*"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-primary-500 file:to-primary-600 file:text-white hover:file:shadow-lg hover:file:shadow-primary-500/50 file:transition-all">
                    <p class="text-gray-400 text-xs mt-1">Affiche verticale (recommandé: 500x750px)</p>
                    @error('cover')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Banner -->
                <div class="md:col-span-2">
                    <label for="banner" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-panorama mr-1"></i> Bannière (Backdrop)
                    </label>
                    <input type="file" name="banner" id="banner" accept="image/*"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-primary-500 file:to-primary-600 file:text-white hover:file:shadow-lg hover:file:shadow-primary-500/50 file:transition-all">
                    <p class="text-gray-400 text-xs mt-1">Image horizontale large (recommandé: 1920x1080px)</p>
                    @error('banner')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Video -->
                <div class="md:col-span-2">
                    <label for="video" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-video mr-1"></i> Fichier vidéo (Optionnel)
                    </label>
                    <input type="file" name="video" id="video" accept="video/*" data-max-file-size="5GB">
                    <input type="hidden" name="video_path" id="video_path">
                    <p class="text-gray-400 text-xs mt-1">
                        <i class="fas fa-info-circle"></i> Formats acceptés: MP4, MKV, AVI, WEBM (Max: 5GB)
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
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
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
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
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
                Enregistrer le média
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

    // Configuration CSRF token
    FilePond.setOptions({
        server: {
            url: '{{ route("upload.chunk") }}',
            process: {
                url: '',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                timeout: 0, // Pas de timeout pour les gros fichiers
                onload: (response) => {
                    const data = JSON.parse(response);
                    // Stocker le chemin du fichier uploadé
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
                    // Nettoyer le champ caché quand l'upload est annulé
                    document.getElementById('video_path').value = '';
                }
            },
            // Configuration pour l'upload par chunks
            patch: null,
            restore: null,
            load: null,
            fetch: null
        }
    });

    // Initialiser FilePond sur le champ vidéo
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
        chunkSize: 2000000, // 2MB par chunk
        chunkRetryDelays: [500, 1000, 3000], // Réessayer en cas d'erreur
        allowRevert: true,
        allowRemove: true,
        allowProcess: true,
        instantUpload: true,
        credits: false,

        // Textes en français
        labelFileProcessing: 'Upload en cours',
        labelFileProcessingComplete: 'Upload terminé',
        labelFileProcessingAborted: 'Upload annulé',
        labelFileProcessingError: 'Erreur lors de l\'upload',
        labelTapToCancel: 'Cliquez pour annuler',
        labelTapToRetry: 'Cliquez pour réessayer',
        labelTapToUndo: 'Cliquez pour annuler',
        labelButtonRemoveItem: 'Supprimer',
        labelButtonAbortItemLoad: 'Annuler',
        labelButtonRetryItemLoad: 'Réessayer',
        labelButtonAbortItemProcessing: 'Annuler',
        labelButtonUndoItemProcessing: 'Annuler',
        labelButtonRetryItemProcessing: 'Réessayer',
        labelButtonProcessItem: 'Uploader',
        fileValidateTypeLabelExpectedTypes: 'Formats acceptés: {allButLastType} ou {lastType}',

        // Callbacks pour le suivi de progression
        onaddfile: (error, file) => {
            if (error) {
                console.error('Erreur ajout fichier:', error);
                return;
            }
            console.log('Fichier ajouté:', file.filename);
        },

        onprocessfile: (error, file) => {
            if (error) {
                console.error('Erreur traitement:', error);
                return;
            }
            console.log('Fichier uploadé avec succès:', file.filename);
        },

        onremovefile: (error, file) => {
            if (error) {
                console.error('Erreur suppression:', error);
                return;
            }
            console.log('Fichier supprimé:', file.filename);
            document.getElementById('video_path').value = '';
        },

        onprocessfileprogress: (file, progress) => {
            // Progression en pourcentage
            const percent = Math.round(progress * 100);
            console.log(`Upload: ${percent}%`);
        },

        onprocessfileabort: (file) => {
            console.log('Upload annulé:', file.filename);
            document.getElementById('video_path').value = '';
        }
    });

    // Optionnel: Initialiser FilePond pour les images aussi (thumbnail, cover, banner)
    const thumbnailPond = FilePond.create(document.getElementById('thumbnail'), {
        labelIdle: 'Glissez-déposez votre vignette ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB', // Augmenté pour accepter de grandes images
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    const coverPond = FilePond.create(document.getElementById('cover'), {
        labelIdle: 'Glissez-déposez votre couverture ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB', // Augmenté pour accepter de grandes images
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    const bannerPond = FilePond.create(document.getElementById('banner'), {
        labelIdle: 'Glissez-déposez votre bannière ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB', // Augmenté pour accepter de grandes images
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });
</script>
@endpush
