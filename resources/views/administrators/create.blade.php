@extends('admin.layouts.app')

@section('title', 'Ajouter un Administrateur - ABBEV')
@section('header', 'Ajouter un Administrateur')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('administrators.index') }}" class="inline-flex items-center text-primary-400 hover:text-primary-300 transition">
        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
    </a>
</div>

<!-- Form Card -->
<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-8">
    <form action="{{ route('administrators.store') }}" method="POST">
        @csrf

        <!-- Name Field -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                Nom complet <span class="text-red-400">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   class="w-full bg-dark-50 border @error('name') border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
            @error('name')
            <p class="mt-2 text-sm text-red-400">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </p>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                Adresse email <span class="text-red-400">*</span>
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full bg-dark-50 border @error('email') border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
            @error('email')
            <p class="mt-2 text-sm text-red-400">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </p>
            @enderror
            <p class="mt-2 text-sm text-gray-400">
                <i class="fas fa-info-circle mr-1"></i> L'utilisateur recevra ses identifiants de connexion par email
            </p>
        </div>

        <!-- Password Field -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                Mot de passe <span class="text-red-400">*</span>
            </label>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   class="w-full bg-dark-50 border @error('password') border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
            @error('password')
            <p class="mt-2 text-sm text-red-400">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </p>
            @enderror
            <p class="mt-2 text-sm text-gray-400">
                <i class="fas fa-info-circle mr-1"></i> Minimum 8 caractères
            </p>
        </div>

        <!-- Password Confirmation Field -->
        <div class="mb-8">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                Confirmer le mot de passe <span class="text-red-400">*</span>
            </label>
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   required
                   class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
        </div>

        <!-- Warning Box -->
        <div class="mb-6 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
                <div>
                    <p class="text-yellow-300 font-medium mb-1">Attention</p>
                    <p class="text-yellow-200 text-sm">
                        En créant ce compte administrateur, vous donnez un accès complet à toutes les fonctionnalités de la plateforme.
                        Assurez-vous de bien connaître cette personne.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button type="submit"
                    class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition flex-1">
                <i class="fas fa-check mr-2"></i> Créer l'administrateur
            </button>
            <a href="{{ route('administrators.index') }}"
               class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition text-center">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
        </div>
    </form>
</div>
@endsection
