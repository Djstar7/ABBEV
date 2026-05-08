@extends('layouts.app')

@section('title', 'Modifier la catégorie - Movie Dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-sky-primary">Modifier la catégorie</h1>
    </div>

    <div class="bg-dark-card rounded-lg shadow-lg p-8 border border-sky-primary/10">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                    Nom de la catégorie <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full bg-dark-bg border border-gray-600 rounded-md px-4 py-2 text-white focus:outline-none focus:border-sky-primary"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    Description
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full bg-dark-bg border border-gray-600 rounded-md px-4 py-2 text-white focus:outline-none focus:border-sky-primary">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('categories.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md transition">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-sky-primary hover:bg-sky-light text-white px-6 py-2 rounded-md transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
