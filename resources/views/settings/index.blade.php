@extends('admin.layouts.app')

@section('title', 'Configuration')
@section('header', 'Paramètres & Configuration')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-white mb-2">Configuration</h2>
    <p class="text-gray-400">Gérez les paramètres de votre plateforme ABBEV</p>
</div>

<!-- Settings Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- General Settings -->
    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-primary-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-xl text-primary-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Paramètres Généraux</h3>
                <p class="text-sm text-gray-400">Configuration de base de l'application</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Nom de l'application</span>
                <span class="text-white font-medium">ABBEV</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Version</span>
                <span class="text-white font-medium">1.0.0</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Mode</span>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-medium">Production</span>
            </div>
        </div>
    </div>

    <!-- Media Settings -->
    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-film text-xl text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Paramètres Médias</h3>
                <p class="text-sm text-gray-400">Configuration des contenus</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Upload max</span>
                <span class="text-white font-medium">2 GB</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Formats acceptés</span>
                <span class="text-white font-medium">MP4, AVI, MKV</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Qualité par défaut</span>
                <span class="text-white font-medium">1080p</span>
            </div>
        </div>
    </div>

    <!-- User Settings -->
    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-xl text-purple-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Paramètres Utilisateurs</h3>
                <p class="text-sm text-gray-400">Gestion des comptes utilisateurs</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Inscription publique</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Vérification email</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Connexions sociales</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shield-alt text-xl text-red-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Sécurité</h3>
                <p class="text-sm text-gray-400">Paramètres de sécurité</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Authentification 2FA</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Logs d'activité</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
            <div class="flex justify-between items-center p-3 bg-dark-50 rounded-lg">
                <span class="text-gray-300">Sessions multiples</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-dark-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="mt-8 flex justify-end gap-4">
    <button class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300">
        Annuler
    </button>
    <button class="bg-gradient-to-r from-primary-500 to-primary-600 hover:shadow-lg hover:shadow-primary-500/50 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300">
        <i class="fas fa-save mr-2"></i>
        Enregistrer les modifications
    </button>
</div>
@endsection
