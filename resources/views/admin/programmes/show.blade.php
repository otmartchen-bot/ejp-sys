@extends('layouts.admin')

@section('title', $programme->titre)
@section('subtitle', 'Détails du programme')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $programme->titre }}</h1>
                <p class="text-gray-600">
                    {{ $programme->date_programme->format('d/m/Y à H:i') }}
                    @if($programme->date_programme->isPast())
                        <span class="ml-2 text-sm text-gray-500">(Passé)</span>
                    @elseif($programme->date_programme->isToday())
                        <span class="ml-2 text-sm text-green-600">(Aujourd'hui)</span>
                    @else
                        <span class="ml-2 text-sm text-blue-600">(À venir)</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.programmes.edit', $programme) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('admin.programmes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <p class="text-sm text-gray-600">Total participants</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['presents'] }}</div>
                <p class="text-sm text-gray-600">Présents</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600">{{ $stats['absents'] }}</div>
                <p class="text-sm text-gray-600">Absents</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">
                    @if($stats['total'] > 0)
                        {{ round(($stats['presents'] / $stats['total']) * 100) }}%
                    @else
                        0%
                    @endif
                </div>
                <p class="text-sm text-gray-600">Taux présence</p>
            </div>
        </div>
    </div>
    
    <!-- Description -->
    @if($programme->description)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
        <p class="text-gray-700">{{ $programme->description }}</p>
    </div>
    @endif
    
    <!-- Liste des participations -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Liste des participants
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Présences/absences pour ce programme
                </p>
            </div>
            <a href="{{ route('admin.programmes.participations', $programme) }}" 
               class="text-blue-600 hover:text-blue-900 font-medium">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if($participations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aide</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif absence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enregistré le</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($participations as $participation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">
                                    {{ $participation->nouveau->prenom }} {{ $participation->nouveau->nom }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $participation->nouveau->profession }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $participation->nouveau->aide->name ?? 'Non assigné' }}</div>
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
                                <div class="text-sm text-gray-900">
                                    {{ $participation->motif_absence ?: 'Non spécifié' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $participation->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($participations->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $participations->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun participant</h3>
                <p class="text-gray-500">Aucune participation enregistrée pour ce programme</p>
            </div>
        @endif
    </div>
</div>
@endsection