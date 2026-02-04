@extends('layouts.admin')

@section('title', 'Créer un Programme')
@section('subtitle', 'Ajouter un nouveau programme d\'intégration')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Nouveau Programme
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Remplissez les informations pour créer un nouveau programme
            </p>
        </div>
        
        <form method="POST" action="{{ route('admin.programmes.store') }}" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">
                        Titre du programme *
                    </label>
                    <input type="text" name="titre" id="titre" required
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                  sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                  hover:border-blue-300"
                           placeholder="Ex: Culte de Jeunesse, Étude Biblique...">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                     sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                     hover:border-blue-300"
                              placeholder="Description détaillée du programme..."></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date du programme -->
                    <div>
                        <label for="date_programme" class="block text-sm font-medium text-gray-700">
                            Date du programme *
                        </label>
                        <input type="date" name="date_programme" id="date_programme" required
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                      sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                      hover:border-blue-300">
                        @error('date_programme')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Lieu -->
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-gray-700">
                            Lieu
                        </label>
                        <input type="text" name="lieu" id="lieu"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                      sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                      hover:border-blue-300"
                               placeholder="Ex: Temple principal, Salle de réunion...">
                        @error('lieu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Heure de début -->
                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-gray-700">
                            Heure de début *
                        </label>
                        <input type="time" name="heure_debut" id="heure_debut" required
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                      sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                      hover:border-blue-300">
                        @error('heure_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Heure de fin -->
                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-gray-700">
                            Heure de fin (optionnel)
                        </label>
                        <input type="time" name="heure_fin" id="heure_fin"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                                      sm:text-sm border-gray-300 rounded-md transition-all duration-200
                                      hover:border-blue-300">
                        @error('heure_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.programmes.index') }}" 
                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm 
                          font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                          transition-all duration-200">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm 
                               text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                               transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> Créer le programme
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Définir la date minimale comme aujourd'hui
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_programme').min = today;
    
    // Effet glassmorphism sur les inputs
    document.querySelectorAll('input, textarea').forEach(element => {
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