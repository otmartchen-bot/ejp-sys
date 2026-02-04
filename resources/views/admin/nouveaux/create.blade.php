@extends('layouts.admin')

@section('title', 'Ajouter un Nouveau')
@section('subtitle', 'Création d\'un nouveau inscrit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8">
            <div class="flex items-center">
                <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-white">Ajouter un Nouveau</h1>
                    <p class="text-green-100">Formulaire d'inscription</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.nouveaux.store') }}" 
              class="px-6 py-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                        Prénom *
                    </label>
                    <input type="text" name="prenom" id="prenom" required
                           value="{{ old('prenom') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400"
                           placeholder="Prénom">
                    @error('prenom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom *
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400"
                           placeholder="Nom">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input type="email" name="email" id="email" required
                       value="{{ old('email') }}"
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-green-500 focus:border-green-500 
                              transition-all duration-200 hover:border-green-400"
                       placeholder="exemple@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profession -->
                <div>
                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                        Profession *
                    </label>
                    <input type="text" name="profession" id="profession" required
                           value="{{ old('profession') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400"
                           placeholder="Ex: Étudiant, Employé, Commerçant...">
                    @error('profession')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- FIJ -->
                <div>
                    <label for="fij" class="block text-sm font-medium text-gray-700 mb-2">
                        FIJ *
                    </label>
                    <input type="text" name="fij" id="fij" required
                           value="{{ old('fij') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400"
                           placeholder="Ex: FIJ 2024, FIJ 2025...">
                    @error('fij')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone (optionnel)
                    </label>
                    <input type="tel" name="telephone" id="telephone"
                           value="{{ old('telephone') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400"
                           placeholder="Ex: +229 XX XX XX XX">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Date d'enregistrement -->
                <div>
                    <label for="date_enregistrement" class="block text-sm font-medium text-gray-700 mb-2">
                        Date d'enregistrement *
                    </label>
                    <input type="date" name="date_enregistrement" id="date_enregistrement" required
                           value="{{ old('date_enregistrement', date('Y-m-d')) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                  transition-all duration-200 hover:border-green-400">
                    @error('date_enregistrement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Aide assigné -->
            <div>
                <label for="aide_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Aide à l'intégration assigné (optionnel)
                </label>
                <select name="aide_id" id="aide_id"
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                               focus:ring-2 focus:ring-green-500 focus:border-green-500 
                               transition-all duration-200 hover:border-green-400">
                    <option value="">-- Sélectionner un aide --</option>
                    @foreach($aides as $aide)
                        <option value="{{ $aide->id }}" {{ old('aide_id') == $aide->id ? 'selected' : '' }}>
                            {{ $aide->name }} ({{ $aide->email }})
                        </option>
                    @endforeach
                </select>
                @error('aide_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.nouveaux.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 
                          hover:bg-gray-50 transition-colors duration-200 font-medium">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 
                               text-white rounded-lg hover:from-green-600 hover:to-green-700 
                               transition-all duration-200 shadow-lg hover:shadow-xl font-medium
                               transform hover:-translate-y-0.5">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter le nouveau
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Définir la date maximale comme aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_enregistrement').max = today;
    document.getElementById('date_enregistrement').value = today;
});
</script>
@endsection