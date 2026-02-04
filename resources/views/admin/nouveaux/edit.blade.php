@extends('layouts.admin')

@section('title', 'Modifier Nouveau')
@section('subtitle', 'Modification des informations')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <div class="flex items-center">
                <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-edit text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-white">Modifier le Nouveau</h1>
                    <p class="text-blue-100">{{ $nouveau->prenom }} {{ $nouveau->nom }}</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.nouveaux.update', $nouveau->id) }}" 
              class="px-6 py-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                        Prénom *
                    </label>
                    <input type="text" name="prenom" id="prenom" required
                           value="{{ old('prenom', $nouveau->prenom) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400"
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
                           value="{{ old('nom', $nouveau->nom) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400"
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
                       value="{{ old('email', $nouveau->email) }}"
                       class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              transition-all duration-200 hover:border-blue-400"
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
                           value="{{ old('profession', $nouveau->profession) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400"
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
                           value="{{ old('fij', $nouveau->fij) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400"
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
                           value="{{ old('telephone', $nouveau->telephone) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400"
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
                           value="{{ old('date_enregistrement', $nouveau->date_enregistrement) }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  transition-all duration-200 hover:border-blue-400">
                    @error('date_enregistrement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Aide assigné -->
            <div>
                <label for="aide_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Aide à l'intégration assigné
                </label>
                <select name="aide_id" id="aide_id"
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               transition-all duration-200 hover:border-blue-400">
                    <option value="">-- Sélectionner un aide --</option>
                    @foreach($aides as $aide)
                        <option value="{{ $aide->id }}" 
                                {{ old('aide_id', $nouveau->aide_id) == $aide->id ? 'selected' : '' }}>
                            {{ $aide->name }} ({{ $aide->email }})
                        </option>
                    @endforeach
                </select>
                @error('aide_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Laissez vide si le nouveau n'est pas encore assigné à un aide
                </p>
            </div>
            
                        <!-- Boutons AMÉLIORÉS -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-gray-200">
                <!-- Bouton Annuler (Retour au détail) -->
                <a href="{{ route('admin.nouveaux.show', $nouveau->id) }}" 
                   class="inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 
                          rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400
                          transition-all duration-300 font-medium shadow-sm hover:shadow-md
                          transform hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-arrow-left mr-2.5"></i> Retour sans enregistrer
                </a>
                
                <!-- Bouton Enregistrer (PRINCIPAL avec effet) -->
                <button type="submit"
                        class="inline-flex items-center justify-center px-8 py-3.5 
                               bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 
                               text-white rounded-xl hover:from-blue-700 hover:via-blue-600 hover:to-blue-700
                               transition-all duration-300 font-semibold shadow-lg 
                               hover:shadow-xl transform hover:-translate-y-1 active:translate-y-0
                               focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-opacity-50
                               relative overflow-hidden group">
                    <!-- Effet de brillance -->
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent 
                           -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                    <i class="fas fa-save mr-2.5 relative z-10"></i>
                    <span class="relative z-10">Enregistrer les modifications</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Effets glassmorphism sur les inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('focus', () => {
            element.parentElement.classList.add('glassmorphism-input');
        });
        
        element.addEventListener('blur', () => {
            element.parentElement.classList.remove('glassmorphism-input');
        });
    });
    
    // Définir la date maximale comme aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_enregistrement').max = today;
});
</script>

<style>
.glassmorphism-input {
    background: rgba(59, 130, 246, 0.05);
    border-radius: 0.75rem;
    padding: 0.75rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
}
</style>
@endsection