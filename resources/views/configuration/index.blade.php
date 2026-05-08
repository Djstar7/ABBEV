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

<form action="{{ route('configuration.update') }}" method="POST" x-data="configForm()">
    @csrf

    <div class="space-y-6">
        @foreach($configurations as $group => $configs)
        <!-- Configuration Group Card -->
        <div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 overflow-hidden">
            <!-- Group Header -->
            <div class="bg-dark-50 px-6 py-4 border-b border-dark-200 flex items-center justify-between cursor-pointer"
                 @click="toggleGroup('{{ $group }}')">
                <div class="flex items-center">
                    @if($group === 'general')
                    <i class="fas fa-cog text-2xl text-primary-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Général</h3>
                    @elseif($group === 'maintenance')
                    <i class="fas fa-tools text-2xl text-orange-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Maintenance</h3>
                    @elseif($group === 'system')
                    <i class="fas fa-server text-2xl text-cyan-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Système</h3>
                    @elseif($group === 'paypal')
                    <i class="fab fa-paypal text-2xl text-blue-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">PayPal</h3>
                    @elseif($group === 'fedapay')
                    <i class="fas fa-credit-card text-2xl text-indigo-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">FedaPay</h3>
                    @elseif($group === 'freemopay')
                    <i class="fas fa-wallet text-2xl text-green-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">FreeMoPay</h3>
                    @elseif($group === 'nexah_sms')
                    <i class="fas fa-sms text-2xl text-purple-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Nexah SMS</h3>
                    @elseif($group === 'whatsapp')
                    <i class="fab fa-whatsapp text-2xl text-green-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">WhatsApp Business</h3>
                    @elseif($group === 'promo')
                    <i class="fas fa-tag text-2xl text-yellow-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Code Promo</h3>
                    @elseif($group === 'notifications')
                    <i class="fas fa-bell text-2xl text-pink-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Notifications</h3>
                    @elseif($group === 'security')
                    <i class="fas fa-shield-alt text-2xl text-red-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white">Sécurité</h3>
                    @else
                    <i class="fas fa-cog text-2xl text-gray-400 mr-3"></i>
                    <h3 class="text-xl font-bold text-white capitalize">{{ str_replace('_', ' ', $group) }}</h3>
                    @endif
                </div>
                <i class="fas fa-chevron-down text-gray-400 transition-transform"
                   :class="{ 'rotate-180': openGroups.includes('{{ $group }}') }"></i>
            </div>

            <!-- Group Content -->
            <div x-show="openGroups.includes('{{ $group }}')"
                 x-transition
                 class="p-6">
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
        </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-4">
        <button type="submit"
                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
        </button>
        <button type="button"
                @click="window.location.reload()"
                class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-undo mr-2"></i> Annuler
        </button>
    </div>

    <!-- Warning Box -->
    <div class="mt-6 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="text-yellow-300 font-medium mb-1">Attention</p>
                <p class="text-yellow-200 text-sm">
                    Assurez-vous de tester vos configurations en mode sandbox avant de passer en production.
                    Des paramètres incorrects peuvent empêcher les paiements de fonctionner correctement.
                </p>
            </div>
        </div>
    </div>
</form>

<script>
function configForm() {
    return {
        openGroups: ['general', 'system', 'paypal', 'fedapay', 'freemopay', 'nexah_sms', 'whatsapp', 'promo', 'notifications', 'security'],
        toggleGroup(group) {
            if (this.openGroups.includes(group)) {
                this.openGroups = this.openGroups.filter(g => g !== group);
            } else {
                this.openGroups.push(group);
            }
        }
    }
}
</script>
@endsection
