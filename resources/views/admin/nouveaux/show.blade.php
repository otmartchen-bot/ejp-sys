@extends('layouts.admin')

@section('title', 'Détails du Nouveau')
@section('subtitle', 'Informations complètes et historique')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête avec boutons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-user text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $nouveau->prenom }} {{ $nouveau->nom }}</h1>
                <p class="text-gray-600">{{ $nouveau->email }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.nouveaux.edit', $nouveau) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <form action="{{ route('admin.nouveaux.destroy', $nouveau) }}" method="POST" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce nouveau ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash mr-2"></i> Supprimer
                </button>
            </form>
            <a href="{{ route('admin.nouveaux.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>
    
    <!-- Informations personnelles -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Prénom</p>
                        <p class="font-medium">{{ $nouveau->prenom }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nom</p>
                        <p class="font-medium">{{ $nouveau->nom }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $nouveau->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Téléphone</p>
                    <p class="font-medium">{{ $nouveau->telephone ?? 'Non spécifié' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Profession</p>
                    <p class="font-medium">{{ $nouveau->profession }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">FIJ</p>
                    <p class="font-medium">{{ $nouveau->fij }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date d'enregistrement</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($nouveau->date_enregistrement)->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aide assigné</p>
                    <p class="font-medium">
                        @if($nouveau->aide)
                            <span class="text-blue-600">{{ $nouveau->aide->name }}</span>
                            <span class="text-sm text-gray-500">({{ $nouveau->aide->email }})</span>
                        @else
                            <span class="text-red-500">Non assigné</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques de participation</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total participations</span>
                    <span class="font-bold text-blue-600 text-xl">{{ $totalParticipations }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Présences</span>
                    <span class="font-bold text-green-600 text-xl">{{ $presences }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Absences</span>
                    <span class="font-bold text-red-600 text-xl">{{ $absences }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Taux de présence</span>
                    <span class="font-bold text-{{ $taux >= 80 ? 'green' : ($taux >= 50 ? 'yellow' : 'red') }}-600 text-xl">
                        {{ $taux }}%
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Statut</span>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        bg-{{ $statut['couleur'] }}-100 
                        text-{{ $statut['couleur'] }}-800 uppercase">
                        @if($statut['label'] == 'actif')
                            Actif <i class="fas fa-check ml-1"></i>
                        @elseif($statut['label'] == 'moyen')
                            Moyen <i class="fas fa-exclamation ml-1"></i>
                        @else
                            Inactif <i class="fas fa-times ml-1"></i>
                        @endif
                    </span>
                </div>
            </div>
            
            @if($nouveau->aide)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Responsable :</h4>
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-shield text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $nouveau->aide->name }}</p>
                            <p class="text-sm text-gray-600">{{ $nouveau->aide->email }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Historique des participations -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Historique des participations
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Toutes les participations aux programmes
            </p>
        </div>
        
        @if($participations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif absence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marqué par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enregistré le</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($participations as $participation)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($participation->programme)
                                        {{ $participation->programme->date_programme->format('d/m/Y') }}
                                    @else
                                        Date inconnue
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $participation->programme->titre ?? 'Programme inconnu' }}
                                </div>
                                @if($participation->programme)
                                <div class="text-sm text-gray-500">
                                    {{ $participation->programme->heure_debut }}
                                    @if($participation->programme->heure_fin)
                                        - {{ $participation->programme->heure_fin }}
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($participation->present)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Présent
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Absent
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    {{ $participation->motif_absence ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $participation->marquePar->name ?? 'Système' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $participation->enregistre_le ? $participation->enregistre_le->format('d/m/Y H:i') : $participation->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune participation</h3>
                <p class="text-gray-500">Ce nouveau n'a participé à aucun programme pour le moment</p>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session('success') }}');
});
</script>
@endif
@endsection