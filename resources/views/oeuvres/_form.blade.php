@csrf
@php $o = $oeuvre ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Titre <span class="text-red-400">*</span></label>
        <input type="text" name="title" value="{{ old('title', $o->title ?? '') }}" required
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
        @error('title')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Auteur</label>
        <input type="text" name="author" value="{{ old('author', $o->author ?? '') }}"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-2">Rubrique <span class="text-red-400">*</span></label>
    <select name="rubrique_id" required
            class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
        <option value="">— Choisir —</option>
        @foreach($rubriques as $rub)
            <option value="{{ $rub->id }}" @selected(old('rubrique_id', $o->rubrique_id ?? '') == $rub->id)>{{ $rub->name }}</option>
        @endforeach
    </select>
    @error('rubrique_id')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
    <p class="text-gray-500 text-xs mt-1">Seules les rubriques de type « Œuvres » apparaissent ici.</p>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
    <textarea name="description" rows="3"
              class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">{{ old('description', $o->description ?? '') }}</textarea>
</div>

<div class="grid sm:grid-cols-3 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de pages</label>
        <input type="number" name="pages" min="1" value="{{ old('pages', $o->pages ?? '') }}"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Ordre</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $o->sort_order ?? 0) }}"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500">
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-3 cursor-pointer pb-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $o->is_active ?? true)) class="w-4 h-4 accent-primary-500">
            <span class="text-white text-sm">Active</span>
        </label>
    </div>
</div>

<div class="grid sm:grid-cols-2 gap-6 mb-8">
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Couverture (image)</label>
        <input type="file" name="cover" accept="image/*"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2.5 text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-primary-500 file:text-white">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">
            Fichier PDF @if(!isset($o) || !$o->file_path)<span class="text-red-400">*</span>@endif
        </label>
        <input type="file" name="file" accept="application/pdf"
               class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-2.5 text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-primary-500 file:text-white">
        @if(isset($o) && $o->file_path)<p class="text-green-400 text-xs mt-1"><i class="fas fa-check mr-1"></i>PDF actuel conservé si vide.</p>@endif
        @error('file')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
    </div>
</div>

<div class="flex gap-4">
    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition flex-1"><i class="fas fa-save mr-2"></i> Enregistrer</button>
    <a href="{{ route('oeuvres.index') }}" class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition text-center">Annuler</a>
</div>
