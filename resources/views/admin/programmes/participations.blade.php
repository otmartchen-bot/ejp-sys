@extends('layouts.admin')

@section('title', 'Participants')
@section('subtitle', $programme->titre)

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('admin.programmes.show', $programme) }}" 
               class="text-blue-600 hover:text-blue-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Participants</h1>
                <p class="text-gray-600">Programme : {{ $programme->titre }}</p>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            {{ $programme->date_programme->format('d/m/Y à H:i') }}
        </div>
    </div>
    
    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $participations->total() }}</div>
                <p class="text-sm text-gray-600">Total enregistrements</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="text-center">
                @php
                    $presents = $participations->where('present', true)->count();
                @endphp
                <div class="text-2xl font-bold text-green-600">{{ $presents }}</div>
                <p class="text-sm text-gray-600">Présents</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="text-center">
                @php
                    $absents = $participations->where('present', false)->count();
                @endphp
                <div class="text-2xl font-bold text-red-600">{{ $absents }}</div>
                <p class="text-sm text-gray-600">Absents</p>
            </div>
        </div>
    </div>
    
    <!-- Tableau complet -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Tous les participants
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Liste complète des présences/absences
            </p>
        </div>
        
        @if($participations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aide</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enregistré par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($participations as $participation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration + (($participations->currentPage() - 1) * $participations->perPage()) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">
                                    {{ $participation->nouveau->prenom }} {{ $participation->nouveau->nom }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $participation->nouveau->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $participation->nouveau->aide->name ?? '-' }}</div>
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
                                    {{ $participation->nouveau->aide->name ?? 'Admin' }}
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
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $participations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune participation</h3>
                <p class="text-gray-500">Aucun enregistrement pour ce programme</p>
            </div>
        @endif
    </div>
</div>
@endsection