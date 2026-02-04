@extends('layouts.admin')

@section('title', 'Modifier Aide')
@section('subtitle', 'Modification des informations')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200">
        
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-3">
                    <i class="fas fa-user-edit text-yellow-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Modifier l'Aide</h2>
                    <p class="text-sm text-gray-600">{{ $user->name }} • {{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.aides.update', $user->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet *
                </label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
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
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                              transition-all duration-200"
                       placeholder="exemple@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Téléphone -->
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                    Téléphone (optionnel)
                </label>
                <input type="tel" name="telephone" id="telephone"
                       value="{{ old('telephone', $user->telephone) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                              focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                              transition-all duration-200"
                       placeholder="Ex: +229 XX XX XX XX">
                @error('telephone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Section mot de passe (optionnel) -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Changer le mot de passe (optionnel)</h3>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau mot de passe
                        </label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                                      transition-all duration-200"
                               placeholder="Laissez vide pour ne pas modifier">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                                      transition-all duration-200"
                               placeholder="Confirmez le nouveau mot de passe">
                    </div>
                </div>
            </div>
            
            <!-- Section BOUTONS - TRÈS VISIBLE -->
            <div class="pt-8 border-t border-gray-300 bg-gray-50 -mx-6 -mb-6 px-6 py-6 rounded-b-xl">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Info -->
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                        Les modifications seront immédiates
                    </div>
                    
                    <!-- Boutons -->
                    <div class="flex flex-wrap gap-3">
                        <!-- Bouton Annuler -->
                        <a href="{{ route('admin.aides.show', $user->id) }}" 
                           class="px-5 py-3 border-2 border-gray-300 rounded-xl text-gray-700 
                                  bg-white hover:bg-gray-50 hover:border-gray-400 
                                  transition-all duration-200 font-semibold
                                  flex items-center justify-center min-w-[120px]
                                  shadow-sm hover:shadow">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        
                        <!-- Bouton Retour liste -->
                        <a href="{{ route('admin.aides.index') }}" 
                           class="px-5 py-3 border-2 border-gray-300 rounded-xl text-gray-700 
                                  bg-white hover:bg-gray-50 hover:border-gray-400 
                                  transition-all duration-200 font-semibold
                                  flex items-center justify-center min-w-[120px]
                                  shadow-sm hover:shadow">
                            <i class="fas fa-list mr-2"></i> Liste
                        </a>
                        
                        <!-- Bouton Enregistrer - TRÈS VISIBLE -->
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 
                                       text-white rounded-xl hover:from-yellow-600 hover:to-yellow-700 
                                       transition-all duration-200 font-bold
                                       flex items-center justify-center min-w-[160px]
                                       shadow-lg hover:shadow-xl transform hover:-translate-y-1
                                       border-2 border-yellow-700">
                            <i class="fas fa-save mr-2 text-lg"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
            <!-- FIN SECTION BOUTONS -->
            
        </form>
    </div>
</div>

<!-- CSS pour garantir la visibilité -->
<style>
/* Style FORCÉ pour le bouton Enregistrer */
button[type="submit"] {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    border: 2px solid #b45309 !important;
    border-radius: 12px !important;
    padding: 12px 24px !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 160px !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3) !important;
    transition: all 0.3s !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4) !important;
    border-color: #92400e !important;
}

button[type="submit"]:active {
    transform: translateY(-1px) !important;
}

/* Style pour bouton Annuler */
a.bg-white {
    border: 2px solid #d1d5db !important;
    border-radius: 12px !important;
    padding: 12px 20px !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 120px !important;
    background: white !important;
    color: #374151 !important;
    text-decoration: none !important;
}

a.bg-white:hover {
    background: #f9fafb !important;
    border-color: #9ca3af !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

/* Conteneur boutons visible */
.bg-gray-50 {
    background: #f9fafb !important;
    margin-top: 2rem !important;
    padding: 1.5rem !important;
    border-top: 2px solid #e5e7eb !important;
}
</style>

<!-- Script pour vérifier et forcer l'affichage -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE MODIFICATION AIDE ===');
    
    // Vérifier le bouton Enregistrer
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        console.log('✅ BOUTON ENREGISTRER TROUVÉ:', submitBtn);
        
        // Forcer les styles
        submitBtn.style.display = 'flex';
        submitBtn.style.visibility = 'visible';
        submitBtn.style.opacity = '1';
        submitBtn.style.zIndex = '1000';
        submitBtn.style.position = 'relative';
        
        // Ajouter un contour rouge pour vérifier visuellement
        submitBtn.style.outline = '2px solid red';
        submitBtn.style.outlineOffset = '2px';
        
    } else {
        console.error('❌ BOUTON ENREGISTRER NON TROUVÉ !');
        // Créer un bouton d'urgence si absent
        const form = document.querySelector('form');
        if (form) {
            const emergencyBtn = document.createElement('button');
            emergencyBtn.type = 'submit';
            emergencyBtn.innerHTML = '<i class="fas fa-save mr-2"></i> ENREGISTRER (URGENCE)';
            emergencyBtn.style.cssText = `
                background: red !important;
                color: white !important;
                padding: 15px 30px !important;
                font-size: 18px !important;
                font-weight: bold !important;
                border: none !important;
                border-radius: 10px !important;
                margin-top: 20px !important;
                cursor: pointer !important;
                display: block !important;
                width: 100% !important;
            `;
            form.appendChild(emergencyBtn);
            console.log('⚠️ Bouton urgence ajouté');
        }
    }
    
    // Vérifier les boutons Annuler
    const cancelBtns = document.querySelectorAll('a[href*="aides"]');
    console.log(`✅ ${cancelBtns.length} boutons Annuler/Retour trouvés`);
    
    // Afficher un message de debug
   
});
</script>
@endsection