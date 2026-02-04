@extends('layouts.admin')

@section('title', 'Ajouter un Nouveau')
@section('subtitle', 'Créer un nouveau compte')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Nouveau Membre
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Remplissez les informations pour créer un nouveau compte
            </p>
        </div>
        
        <form method="POST" action="{{ route('admin.nouveaux.store') }}" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Informations personnelles -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Informations personnelles
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700">
                                Prénom *
                            </label>
                            <input type="text" name="prenom" id="prenom" required
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                          sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                          hover:border-blue-300"
                                   placeholder="Ex: Jean">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">
                                Nom *
                            </label>
                            <input type="text" name="nom" id="nom" required
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                          sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                          hover:border-blue-300"
                                   placeholder="Ex: Dupont">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email *
                        </label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                      sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                      hover:border-blue-300"
                               placeholder="exemple@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Profession et FIJ -->
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-briefcase mr-2 text-green-500"></i>
                        Profession & FIJ
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profession -->
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700">
                                Profession *
                            </label>
                            <input type="text" name="profession" id="profession" required
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                          sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                          hover:border-blue-300"
                                   placeholder="Ex: Étudiant, Enseignant, Infirmier...">
                            @error('profession')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- FIJ -->
                        <div>
                            <label for="fij" class="block text-sm font-medium text-gray-700">
                                FIJ *
                            </label>
                            <input type="text" name="fij" id="fij" required
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                          sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                          hover:border-blue-300"
                                   placeholder="Ex: FIJ-2024-001">
                            @error('fij')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Assignation et date -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        Assignation & Date
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Assignation à un aide -->
                        <div>
                            <label for="aide_id" class="block text-sm font-medium text-gray-700">
                                Assigner à un aide
                            </label>
                            <select name="aide_id" id="aide_id"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 
                                           focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                           sm:text-sm rounded-md transition-all duration-200">
                                <option value="">-- Sélectionnez un aide --</option>
                                @foreach($aides as $aide)
                                    <option value="{{ $aide->id }}" 
                                            {{ old('aide_id') == $aide->id ? 'selected' : '' }}>
                                        {{ $aide->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('aide_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Date d'enregistrement -->
                        <div>
                            <label for="date_enregistrement" class="block text-sm font-medium text-gray-700">
                                Date d'enregistrement *
                            </label>
                            <input type="date" name="date_enregistrement" id="date_enregistrement" required
                                   value="{{ old('date_enregistrement', date('Y-m-d')) }}"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                          sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                          hover:border-blue-300">
                            @error('date_enregistrement')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.nouveaux.index') }}" 
                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm 
                          font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                          transition-all duration-200">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm 
                               text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500
                               transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> Créer le nouveau
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Effet glassmorphism sur les inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('focus', () => {
            element.parentElement.classList.add('glassmorphism-input');
        });
        
        element.addEventListener('blur', () => {
            element.parentElement.classList.remove('glassmorphism-input');
        });
    });
});
</script>

<style>
.glassmorphism-input {
    background: rgba(59, 130, 246, 0.05);
    border-radius: 0.5rem;
    padding: 0.5rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}
</style>
@endsection