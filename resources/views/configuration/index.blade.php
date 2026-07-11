@extends('admin.layouts.app')

@section('title', 'Configuration - ABBEV')
@section('header', 'Configuration du Système')

@section('content')
<!-- Info Banner -->
<div class="mb-6 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
    <div class="flex items-start">
        <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
        <div>
            <p class="text-blue-300 font-medium mb-1">Information importante</p>
            <p class="text-blue-200 text-sm">
                Ces paramètres contrôlent les systèmes de paiement et de notification de votre plateforme.
                Modifiez-les avec précaution. Les clés secrètes sont masquées par défaut.
            </p>
        </div>
    </div>
</div>

{{-- KPay test form (hidden) --}}
<form id="kpay-test-form" action="{{ route('configuration.testKpay') }}" method="POST" class="hidden">@csrf</form>

@if(session('error'))
<div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
    <div class="flex items-start">
        <i class="fas fa-times-circle text-red-400 text-xl mr-3 mt-1"></i>
        <div>
            <p class="text-red-300 font-medium mb-1">Erreur</p>
            <p class="text-red-200 text-sm">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('success'))
<div class="mb-6 bg-green-500/10 border border-green-500/30 rounded-lg p-4">
    <div class="flex items-start">
        <i class="fas fa-check-circle text-green-400 text-xl mr-3 mt-1"></i>
        <div>
            <p class="text-green-300 font-medium mb-1">Succès</p>
            <p class="text-green-200 text-sm">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@php
    $groupMeta = [
        'general'       => ['icon' => 'fas fa-cog',          'color' => 'text-primary-400', 'label' => 'Général'],
        'maintenance'   => ['icon' => 'fas fa-tools',        'color' => 'text-orange-400',  'label' => 'Maintenance'],
        'system'        => ['icon' => 'fas fa-server',       'color' => 'text-cyan-400',    'label' => 'Système'],
        'video_mode'    => ['icon' => 'fas fa-video',        'color' => 'text-fuchsia-400', 'label' => 'Mode Vidéo'],
        'paypal'        => ['icon' => 'fab fa-paypal',       'color' => 'text-blue-400',    'label' => 'PayPal'],
        'stripe'        => ['icon' => 'fab fa-cc-stripe',    'color' => 'text-violet-400',  'label' => 'Stripe (Carte)'],
        'fedapay'       => ['icon' => 'fas fa-credit-card',  'color' => 'text-indigo-400',  'label' => 'FedaPay'],
        'freemopay'     => ['icon' => 'fas fa-wallet',       'color' => 'text-green-400',   'label' => 'FreeMoPay'],
        'kpay'          => ['icon' => 'fas fa-mobile-alt',   'color' => 'text-emerald-400', 'label' => 'KPay'],
        'nowpayments'   => ['icon' => 'fab fa-bitcoin',      'color' => 'text-amber-400',   'label' => 'Crypto (NOWPayments)'],
        'nexah_sms'     => ['icon' => 'fas fa-sms',          'color' => 'text-purple-400',  'label' => 'Nexah SMS'],
        'whatsapp'      => ['icon' => 'fab fa-whatsapp',     'color' => 'text-green-400',   'label' => 'WhatsApp Business'],
        'promo'         => ['icon' => 'fas fa-tag',          'color' => 'text-yellow-400',  'label' => 'Code Promo'],
        'notifications' => ['icon' => 'fas fa-bell',         'color' => 'text-pink-400',    'label' => 'Notifications'],
        'security'      => ['icon' => 'fas fa-shield-alt',   'color' => 'text-red-400',     'label' => 'Sécurité'],
    ];
    $defaultTab = session('active_tab', $configurations->keys()->first());
@endphp

<div x-data="configForm('{{ $defaultTab }}')">

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-dark-200 flex flex-wrap gap-1">
        @foreach($configurations as $group => $configs)
        @php $meta = $groupMeta[$group] ?? ['icon' => 'fas fa-cog', 'color' => 'text-gray-400', 'label' => ucfirst(str_replace('_', ' ', $group))]; @endphp
        <button type="button"
                @click="activeTab = '{{ $group }}'"
                :class="activeTab === '{{ $group }}' ? 'border-primary-500 text-white bg-dark-100' : 'border-transparent text-gray-400 hover:text-white'"
                class="px-4 py-3 border-b-2 rounded-t-lg transition flex items-center text-sm font-medium">
            <i class="{{ $meta['icon'] }} {{ $meta['color'] }} mr-2"></i>
            {{ $meta['label'] }}
        </button>
        @endforeach
    </div>

    <!-- Tab Panels : un formulaire indépendant par groupe -->
    @foreach($configurations as $group => $configs)
    @php $meta = $groupMeta[$group] ?? ['icon' => 'fas fa-cog', 'color' => 'text-gray-400', 'label' => ucfirst(str_replace('_', ' ', $group))]; @endphp
    <div x-show="activeTab === '{{ $group }}'" x-transition>
        <form action="{{ route('configuration.updateGroup', $group) }}" method="POST">
            @csrf

            <!-- Configuration Group Card -->
            <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
                <!-- Group Header -->
                <div class="bg-dark-50 px-6 py-4 border-b border-dark-200 flex items-center">
                    <i class="{{ $meta['icon'] }} text-2xl {{ $meta['color'] }} mr-3"></i>
                    <h3 class="text-xl font-bold text-white">{{ $meta['label'] }}</h3>
                </div>

                <!-- Group Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($configs as $config)
                    <div>
                        <label for="config_{{ $config->key }}" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ $config->description ?? $config->key }}
                            @if($config->key === 'enabled' || str_ends_with($config->key, '_enabled'))
                            @else
                            <span class="text-red-400">*</span>
                            @endif
                        </label>

                        @if($config->key === 'enabled' || str_ends_with($config->key, '_enabled'))
                        <!-- Toggle Switch for Enable/Disable -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="configs[{{ $config->key }}]"
                                   id="config_{{ $config->key }}"
                                   value="1"
                                   {{ old('configs.' . $config->key, $config->value) == '1' ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-300">
                                {{ old('configs.' . $config->key, $config->value) == '1' ? 'Activé' : 'Désactivé' }}
                            </span>
                        </label>

                        @elseif($config->is_secret)
                        <!-- Secret Input (Password) -->
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="configs[{{ $config->key }}]"
                                   id="config_{{ $config->key }}"
                                   value="{{ old('configs.' . $config->key, $config->value) }}"
                                   placeholder="{{ $config->is_secret ? '••••••••••••' : '' }}"
                                   class="w-full bg-dark-50 border @error('configs.' . $config->key) border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 pr-12 text-white focus:outline-none focus:border-primary-500 transition">
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>

                        @elseif($config->key === 'video_mode')
                        <!-- Mode Vidéo : Production (Bunny) ou Test/Dev (sample public) -->
                        <select name="configs[{{ $config->key }}]"
                                id="config_{{ $config->key }}"
                                class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
                            <option value="production" {{ old('configs.' . $config->key, $config->value) === 'production' ? 'selected' : '' }}>🟢 Production — Bunny Stream</option>
                            <option value="test" {{ old('configs.' . $config->key, $config->value) === 'test' ? 'selected' : '' }}>🧪 Test / Dev — vidéo d'échantillon</option>
                        </select>

                        @elseif(str_contains($config->key, 'mode'))
                        <!-- Mode Selector (Sandbox/Live) -->
                        <select name="configs[{{ $config->key }}]"
                                id="config_{{ $config->key }}"
                                class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
                            <option value="sandbox" {{ old('configs.' . $config->key, $config->value) === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                            <option value="live" {{ old('configs.' . $config->key, $config->value) === 'live' ? 'selected' : '' }}>Live (Production)</option>
                        </select>

                        @elseif(is_numeric($config->value))
                        <!-- Number Input -->
                        <input type="number"
                               name="configs[{{ $config->key }}]"
                               id="config_{{ $config->key }}"
                               value="{{ old('configs.' . $config->key, $config->value) }}"
                               step="any"
                               class="w-full bg-dark-50 border @error('configs.' . $config->key) border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">

                        @elseif(str_contains($config->key, 'url') || str_contains($config->key, 'endpoint'))
                        <!-- URL Input -->
                        <input type="url"
                               name="configs[{{ $config->key }}]"
                               id="config_{{ $config->key }}"
                               value="{{ old('configs.' . $config->key, $config->value) }}"
                               placeholder="https://..."
                               class="w-full bg-dark-50 border @error('configs.' . $config->key) border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">

                        @elseif(strlen($config->value ?? '') > 100)
                        <!-- Textarea for long text -->
                        <textarea name="configs[{{ $config->key }}]"
                                  id="config_{{ $config->key }}"
                                  rows="3"
                                  class="w-full bg-dark-50 border border-dark-200 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">{{ old('configs.' . $config->key, $config->value) }}</textarea>

                        @else
                        <!-- Text Input -->
                        <input type="text"
                               name="configs[{{ $config->key }}]"
                               id="config_{{ $config->key }}"
                               value="{{ old('configs.' . $config->key, $config->value) }}"
                               class="w-full bg-dark-50 border @error('configs.' . $config->key) border-red-500 @else border-dark-200 @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary-500 transition">
                        @endif

                        @error('configs.' . $config->key)
                        <p class="mt-2 text-sm text-red-400">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($group === 'video_mode')
                <div class="px-6 pb-2 -mt-2">
                    <div class="bg-fuchsia-500/10 border border-fuchsia-500/30 rounded-lg p-4 text-sm text-fuchsia-200">
                        <p class="font-medium text-fuchsia-300 mb-1"><i class="fas fa-circle-info mr-1"></i> Comment ça marche</p>
                        <p class="mb-1"><strong>Production</strong> : chaque film/épisode est lu via sa vidéo Bunny Stream (configuration <code>.env</code>, inchangée).</p>
                        <p><strong>Test / Dev</strong> : tout le catalogue lit la vidéo d'échantillon publique ci-dessus, sans toucher à Bunny — idéal pour présenter le catalogue (affiches, synopsis) sans consommer de quota.</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons (par groupe) -->
                <div class="bg-dark-50 px-6 py-4 border-t border-dark-200 flex gap-4">
                    <button type="submit"
                            class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i> Enregistrer « {{ $meta['label'] }} »
                    </button>
                    @if($group === 'kpay')
                    <button type="button"
                            onclick="document.getElementById('kpay-test-form').submit()"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg transition">
                        <i class="fas fa-plug mr-2"></i> Tester la connexion
                    </button>
                    @endif
                    <button type="button"
                            @click="window.location.reload()"
                            class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition">
                        <i class="fas fa-undo mr-2"></i> Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endforeach

    <!-- Warning Box -->
    <div class="mt-6 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="text-yellow-300 font-medium mb-1">Attention</p>
                <p class="text-yellow-200 text-sm">
                    Chaque catégorie se sauvegarde indépendamment : enregistrer KPay ne touche pas
                    aux autres configurations. Testez en mode sandbox avant la production.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Dans @push('scripts') : ce bloc est placé dans #page-scripts, que la
     navigation PJAX RÉ-EXÉCUTE. Un <script> laissé inline dans le contenu de
     page n'est PAS exécuté quand le PJAX l'injecte via innerHTML → l'onglet
     Alpine « configForm » serait alors indéfini et la page casserait. --}}
@push('scripts')
<script>
function configForm(defaultTab) {
    return {
        activeTab: defaultTab,
    }
}
</script>
@endpush
@endsection
