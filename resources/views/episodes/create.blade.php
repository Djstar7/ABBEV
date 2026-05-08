@extends('admin.layouts.app')

@section('title', 'Ajouter un Épisode')
@section('header', 'Ajouter un Épisode')

@push('styles')
<!-- FilePond CSS -->
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<style>
    /* FilePond dark theme */
    .filepond--root { font-family: inherit; }
    .filepond--drop-label { color: #9ca3af !important; }
    .filepond--panel-root { background-color: #18181b !important; border: 2px dashed #27272a !important; }
    .filepond--drip { background-color: rgba(6, 182, 212, 0.1) !important; }
    .filepond--item { background-color: #09090b !important; }
    .filepond--item-panel { background-color: #27272a !important; }
    .filepond--file-info-main, .filepond--file-info-sub { color: #e4e4e7 !important; }
    .filepond--file-action-button { background-color: rgba(6, 182, 212, 0.2) !important; }
    .filepond--file-status-main { color: #06b6d4 !important; }
    .filepond--file-status-sub { color: #9ca3af !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('episodes.index', $season->media) }}"
           class="bg-dark-200 hover:bg-dark-300 text-white px-4 py-2 rounded-lg transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">{{ $season->media->title }}</h2>
            <p class="text-gray-400">Saison {{ $season->season_number }} • Ajouter un nouvel épisode</p>
        </div>
    </div>

    <form action="{{ route('episodes.store', $season) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Episode Info Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-primary-400"></i>
                Informations de l'Épisode
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Episode Number -->
                <div>
                    <label for="episode_number" class="block text-sm font-medium text-gray-300 mb-2">
                        Numéro de l'Épisode <span class="text-primary-400">*</span>
                    </label>
                    <input type="number" name="episode_number" id="episode_number" min="1"
                           value="{{ old('episode_number', $season->episodes->count() + 1) }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500"
                           required>
                    @error('episode_number')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                        Durée (en minutes)
                    </label>
                    <input type="number" name="duration" id="duration" min="1"
                           value="{{ old('duration') }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500"
                           placeholder="45">
                    <p class="text-gray-400 text-xs mt-1">Sera convertie en secondes automatiquement</p>
                    @error('duration')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Titre de l'Épisode <span class="text-primary-400">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500"
                           placeholder="Ex: Pilote, Le commencement..." required>
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Résumé de l'Épisode
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 resize-none"
                              placeholder="Synopsis de l'épisode...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published At -->
                <div class="md:col-span-2">
                    <label for="published_at" class="block text-sm font-medium text-gray-300 mb-2">
                        Date de publication
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at"
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
                    @error('published_at')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Video Upload Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-video text-primary-400"></i>
                Fichier Vidéo de l'Épisode
            </h3>

            <input type="file" name="video" id="video" accept="video/*" data-max-file-size="5GB" required>
            <input type="hidden" name="video_path" id="video_path">

            <p class="text-gray-400 text-sm mt-3">
                <i class="fas fa-info-circle"></i> Formats acceptés: MP4, MKV, AVI, WEBM (Max: 5GB)
                <br>
                <i class="fas fa-rocket"></i> Upload par chunks avec barre de progression
            </p>

            @error('video_path')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Thumbnail Upload Section -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-image text-primary-400"></i>
                Vignette de l'Épisode (Optionnel)
            </h3>

            <input type="file" name="thumbnail" id="thumbnail" accept="image/*">

            <p class="text-gray-400 text-sm mt-3">
                <i class="fas fa-info-circle"></i> Image de prévisualisation pour cet épisode (recommandé: 1280x720px)
            </p>

            @error('thumbnail')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('episodes.index', $season->media) }}"
               class="bg-dark-200 hover:bg-dark-300 text-white px-8 py-3 rounded-lg font-medium transition-all">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 hover:shadow-lg hover:shadow-primary-500/50 text-white px-8 py-3 rounded-lg font-medium transition-all">
                <i class="fas fa-save mr-2"></i>
                Enregistrer l'Épisode
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

<script>
    // Register plugins
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    // Configure FilePond server
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
                    console.error('Upload error:', response);
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

    // Initialize FilePond for video
    const videoPond = FilePond.create(document.getElementById('video'), {
        labelIdle: `
            <div style="text-align: center;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #06b6d4; margin-bottom: 1rem;"></i>
                <p style="color: white; font-weight: 500;">Glissez-déposez votre vidéo ou <span style="color: #06b6d4; text-decoration: underline;">parcourir</span></p>
                <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">Upload par chunks - Annulation possible</p>
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

    // Initialize FilePond for thumbnail
    const thumbnailPond = FilePond.create(document.getElementById('thumbnail'), {
        labelIdle: 'Glissez-déposez une image ou <span class="filepond--label-action">parcourir</span>',
        acceptedFileTypes: ['image/*'],
        maxFileSize: '500MB', // Augmenté pour accepter de grandes images
        imagePreviewHeight: 170,
        credits: false,
        allowRevert: false,
        instantUpload: false
    });

    // Convert duration to seconds before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const durationInput = document.getElementById('duration');
        if (durationInput.value) {
            // Convert minutes to seconds
            durationInput.value = parseInt(durationInput.value) * 60;
        }
    });
</script>
@endpush
