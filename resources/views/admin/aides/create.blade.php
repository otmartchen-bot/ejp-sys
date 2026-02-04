@extends('layouts.admin')

@section('title', 'Créer un Aide')
@section('subtitle', 'Ajouter un nouvel aide')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200">
        
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-gray-50">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Nouvel Aide à l'Intégration</h2>
                    <p class="text-sm text-gray-600">Remplissez les informations obligatoires</p>
                </div>
            </div>
        </div>
        
        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.aides.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet *
                </label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition-all duration-200"
                       placeholder="Ex: Jean Dupont">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input type="email" name="email" id="email" required
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition-all duration-200"
                       placeholder="exemple@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de passe *
                </label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition-all duration-200"
                       placeholder="Minimum 8 caractères">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Confirmation mot de passe -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmer le mot de passe *
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition-all duration-200"
                       placeholder="Répétez le mot de passe">
            </div>
            
            <!-- Section BOUTONS BIEN VISIBLE -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Message d'information -->
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tous les champs marqués * sont obligatoires
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex space-x-3">
                        <!-- Bouton Annuler -->
                        <a href="{{ route('admin.aides.index') }}" 
                           class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 
                                  hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200
                                  font-medium flex items-center">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        
                        <!-- Bouton Enregistrer - TRÈS VISIBLE -->
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg 
                                       hover:bg-blue-700 transition-colors duration-200
                                       font-bold shadow-md hover:shadow-lg
                                       flex items-center transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Créer l'aide
                        </button>
                    </div>
                </div>
            </div>
            <!-- FIN SECTION BOUTONS -->
            
        </form>
    </div>
</div>

<!-- CSS pour s'assurer que les boutons sont visibles -->
<style>
/* Assure que les boutons sont bien visibles */
button[type="submit"] {
    background-color: #2563eb !important; /* blue-600 */
    color: white !important;
    font-weight: 600 !important;
    border: none !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0.625rem 1.5rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.2s !important;
}

button[type="submit"]:hover {
    background-color: #1d4ed8 !important; /* blue-700 */
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important;
}

/* Bouton Annuler */
a.bg-gray-50, a.hover\\:bg-gray-50 {
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    padding: 0.625rem 1.5rem !important;
    border-radius: 0.5rem !important;
    display: inline-flex !important;
    align-items: center !important;
}
</style>

<!-- Script pour vérifier que le bouton est bien présent -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page de création chargée');
    
    // Vérifier que le bouton submit existe
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        console.log('✅ Bouton Enregistrer trouvé:', submitBtn);
        submitBtn.style.backgroundColor = '#2563eb';
        submitBtn.style.color = 'white';
        submitBtn.style.fontWeight = 'bold';
        submitBtn.style.padding = '10px 24px';
        submitBtn.style.borderRadius = '8px';
        submitBtn.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
    } else {
        console.log('❌ Bouton Enregistrer NON trouvé !');
    }
    
    // Vérifier le bouton Annuler
    const cancelBtn = document.querySelector('a[href*="aides.index"]');
    if (cancelBtn) {
        console.log('✅ Bouton Annuler trouvé:', cancelBtn);
    }
});
</script>
@endsection