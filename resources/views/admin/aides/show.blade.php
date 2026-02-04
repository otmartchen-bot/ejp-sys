@extends('layouts.admin')

@section('title', 'Détails Aide')
@section('subtitle', 'Informations et nouveaux assignés')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête avec boutons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-user text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.aides.edit', $user) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent 
                      rounded-md font-semibold text-white hover:bg-yellow-600 transition-colors">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('admin.aides.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent 
                      rounded-md font-semibold text-white hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>
    
    <!-- Informations générales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date d'inscription</p>
                    <p class="font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rôle</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-user-shield mr-1"></i> Aide à l'intégration
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Nouveaux assignés</span>
                    <span class="font-bold text-blue-600 text-xl">{{ $nouveaux->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Présences totales</span>
                    <span class="font-bold text-green-600 text-xl">
                        {{ $nouveaux->sum('participations_count') ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Programmes cette semaine</span>
                    <span class="font-bold text-purple-600 text-xl">
                        {{-- À implémenter : nombre de programmes cette semaine --}}
                        0
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.aides.nouveaux', $user) }}" 
                   class="block w-full text-center px-4 py-3 bg-blue-50 text-blue-700 
                          rounded-lg hover:bg-blue-100 transition-colors duration-200">
                    <i class="fas fa-users mr-2"></i> Voir tous les nouveaux
                </a>
                <a href="{{ route('admin.nouveaux.create') }}?aide_id={{ $user->id }}" 
                   class="block w-full text-center px-4 py-3 bg-green-50 text-green-700 
                          rounded-lg hover:bg-green-100 transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Assigner un nouveau
                </a>
                <button onclick="showPasswordResetModal()" 
                   class="w-full text-center px-4 py-3 bg-yellow-50 text-yellow-700 
                          rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                    <i class="fas fa-key mr-2"></i> Réinitialiser mot de passe
                </button>
            </div>
        </div>
    </div>
    
    <!-- Liste des nouveaux assignés -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Nouveaux assignés
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Liste des nouveaux suivis par cet aide
                </p>
            </div>
            <a href="{{ route('admin.aides.nouveaux', $user) }}" 
               class="text-blue-600 hover:text-blue-900 font-medium transition-colors">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if($nouveaux->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profession
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Présences
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($nouveaux as $nouveau)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">
                                            {{ strtoupper(substr($nouveau->prenom, 0, 1)) }}{{ strtoupper(substr($nouveau->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $nouveau->prenom }} {{ $nouveau->nom }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $nouveau->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $nouveau->profession ?? 'Non spécifié' }}</div>
                                <div class="text-sm text-gray-500">{{ $nouveau->fij ?? 'Sans FIJ' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-center">
                                    <span class="font-bold text-lg">{{ $nouveau->participations_count ?? 0 }}</span>
                                    <div class="text-xs text-gray-500">présences</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // Fonction temporaire pour calculer le statut
                                    $presences = $nouveau->participations_count ?? 0;
                                    $programmesTotal = 4; // À remplacer par le vrai total
                                    $pourcentage = $programmesTotal > 0 ? ($presences / $programmesTotal) * 100 : 0;
                                    
                                    if ($pourcentage >= 80) {
                                        $statut = 'actif';
                                        $couleur = 'bg-green-100 text-green-800';
                                    } elseif ($pourcentage >= 50) {
                                        $statut = 'moyen';
                                        $couleur = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $statut = 'inactif';
                                        $couleur = 'bg-red-100 text-red-800';
                                    }
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $couleur }}">
                                    {{ ucfirst($statut) }} 
                                    @if($programmesTotal > 0)
                                    ({{ number_format($pourcentage, 0) }}%)
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.nouveaux.show', $nouveau) }}" 
                                   class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50"
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50"
                                        title="Retirer"
                                        onclick="if(confirm('Retirer {{ $nouveau->prenom }} {{ $nouveau->nom }} de cet aide ?')) {
                                            document.getElementById('remove-{{ $nouveau->id }}').submit();
                                        }">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                                <form id="remove-{{ $nouveau->id }}" 
                                      action="{{ route('admin.aides.removeNouveau', ['user' => $user->id, 'nouveau' => $nouveau->id]) }}" 
                                      method="POST" class="hidden">
                                    @csrf 
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
                        <!-- Pas de pagination car c'est une collection simple -->
            @if($nouveaux->count() > 5)
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-center">
                <a href="{{ route('admin.aides.nouveaux', $user) }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    Voir tous les {{ $nouveaux->count() }} nouveaux <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun nouveau assigné</h3>
                <p class="text-gray-500 mb-6">Cet aide ne suit actuellement aucun nouveau</p>
                <a href="{{ route('admin.nouveaux.create') }}?aide_id={{ $user->id }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md 
                          hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Assigner un premier nouveau
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour réinitialiser le mot de passe -->
<div id="passwordResetModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Réinitialiser le mot de passe</h3>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-600 mb-4">
                Un nouveau mot de passe temporaire sera généré pour {{ $user->name }}.
            </p>
            {{-- CORRECTION ICI : Supprimé le commentaire HTML problématique --}}
            <form id="resetPasswordForm" action="{{ route('admin.aides.resetPassword', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="temp_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe temporaire (optionnel)
                    </label>
                    <input type="text" name="temp_password" id="temp_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Laissez vide pour générer automatiquement">
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
            <button type="button" 
                    onclick="hidePasswordResetModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                Annuler
            </button>
            <button type="button" 
                    onclick="document.getElementById('resetPasswordForm').submit()"
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow">
                Réinitialiser
            </button>
        </div>
    </div>
</div>

<script>
function showPasswordResetModal() {
    document.getElementById('passwordResetModal').classList.remove('hidden');
    document.getElementById('passwordResetModal').classList.add('flex');
    // Focus sur le champ de mot de passe
    setTimeout(() => {
        document.getElementById('temp_password').focus();
    }, 100);
}

function hidePasswordResetModal() {
    document.getElementById('passwordResetModal').classList.remove('flex');
    document.getElementById('passwordResetModal').classList.add('hidden');
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('passwordResetModal').addEventListener('click', function(e) {
    if (e.target.id === 'passwordResetModal') {
        hidePasswordResetModal();
    }
});

// Fermer la modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hidePasswordResetModal();
    }
});
</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
        className: "toast-success"
    }).showToast();
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Toastify({
        text: "Erreur : {{ session('error') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        className: "toast-error"
    }).showToast();
});
</script>
@endif

<style>
.toast-success {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.toast-error {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Animation pour la modal */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

#passwordResetModal > div {
    animation: modalFadeIn 0.3s ease-out;
}
</style>
@endsection