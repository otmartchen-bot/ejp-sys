@extends('layouts.aide')

@section('title', $nouveau->prenom . ' ' . $nouveau->nom)
@section('subtitle', 'Détails du nouveau')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mr-4">
                <span class="text-white font-bold text-xl">
                    {{ substr($nouveau->prenom, 0, 1) }}{{ substr($nouveau->nom, 0, 1) }}
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $nouveau->prenom }} {{ $nouveau->nom }}</h1>
                <p class="text-gray-600">{{ $nouveau->profession }} • {{ $nouveau->fij }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('aide.nouveaux.edit', $nouveau) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('aide.nouveaux.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>
    
    <!-- Deux colonnes : Informations et Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Colonne gauche : Informations personnelles -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Carte Informations personnelles -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                        Informations personnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom complet</p>
                            <p class="font-medium">{{ $nouveau->prenom }} {{ $nouveau->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $nouveau->email }}</p>
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
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium">{{ $nouveau->telephone ?: 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'enregistrement</p>
                            <p class="font-medium">{{ $nouveau->date_enregistrement->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('aide.nouveaux.edit', $nouveau) }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit mr-1"></i> Modifier ces informations
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Carte Historique des participations -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-green-600"></i>
                            Historique des participations
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('aide.nouveaux.historique', ['nouveau' => $nouveau, 'timeframe' => 'semaine']) }}"
                               class="px-3 py-1 text-sm rounded-lg {{ request('timeframe') == 'semaine' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                Semaine
                            </a>
                            <a href="{{ route('aide.nouveaux.historique', ['nouveau' => $nouveau, 'timeframe' => 'mois']) }}"
                               class="px-3 py-1 text-sm rounded-lg {{ request('timeframe') != 'semaine' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                Mois
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($participations->count() > 0)
                        <div class="space-y-3">
                            @foreach($participations as $participation)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div>
                                    <p class="font-medium">{{ $participation->programme->titre }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $participation->programme->date_programme->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($participation->present)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Présent
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Absent
                                        </span>
                                        @if($participation->motif_absence)
                                        <p class="text-xs text-gray-500 mt-1">{{ $participation->motif_absence }}</p>
                                        @endif
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $participation->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($participations->hasPages())
                            <div class="mt-4 pt-4 border-t">
                                {{ $participations->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">Aucune participation enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne droite : Statistiques et Actions -->
        <div class="space-y-6">
            
            <!-- Carte Statistiques -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Statistiques</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $statut = $nouveau->getStatutAttribute();
                            $totalProgrammes = \App\Models\Programme::where('date_programme', '>=', now()->subDays(30))->count();
                            $presences = $nouveau->participations()->where('present', true)->count();
                        @endphp
                        
                        <div>
                            <p class="text-sm text-gray-500">Statut actuel</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                @if($statut['label'] == 'actif') bg-green-100 text-green-800
                                @elseif($statut['label'] == 'moyen') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ ucfirst($statut['label']) }} ({{ $statut['pourcentage'] }}%)
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Total présences (30j)</p>
                            <p class="text-2xl font-bold">{{ $presences }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Programmes disponibles</p>
                            <p class="text-lg font-medium">{{ $totalProgrammes }}</p>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500">Taux de participation</p>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $statut['pourcentage'] }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">{{ $statut['pourcentage'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Actions rapides -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg text-white">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-bold">Actions rapides</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3">
                        <a href="{{ route('aide.participations.marquer', ['nouveau' => $nouveau->id, 'programme' => 'current']) }}"
                           class="flex items-center p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                            <div class="bg-white/20 p-2 rounded-lg mr-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="font-medium">Marquer présence</p>
                                <p class="text-sm text-blue-200">Pour programme actuel</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('aide.nouveaux.historique', $nouveau) }}"
                           class="flex items-center p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                            <div class="bg-white/20 p-2 rounded-lg mr-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Voir historique complet</p>
                                <p class="text-sm text-blue-200">Toutes participations</p>
                            </div>
                        </a>
                        
                        <a href="mailto:{{ $nouveau->email }}"
                           class="flex items-center p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                            <div class="bg-white/20 p-2 rounded-lg mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="font-medium">Envoyer email</p>
                                <p class="text-sm text-blue-200">{{ $nouveau->email }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Prochain programme -->
            @php
                $nextProgramme = \App\Models\Programme::where('date_programme', '>=', now())
                    ->orderBy('date_programme')
                    ->first();
            @endphp
            @if($nextProgramme)
            <div class="bg-white rounded-xl shadow-lg border border-green-200">
                <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                    <h3 class="text-lg font-bold text-gray-900">Prochain programme</h3>
                </div>
                <div class="p-6">
                    <p class="font-medium">{{ $nextProgramme->titre }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="far fa-clock mr-1"></i>
                        {{ $nextProgramme->date_programme->format('d/m/Y à H:i') }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('aide.participations.marquer', ['nouveau' => $nouveau->id, 'programme' => $nextProgramme->id]) }}"
                           class="inline-flex items-center text-green-600 hover:text-green-800">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Marquer présence/absence
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection