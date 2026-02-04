<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - {{ $nouveau->full_name }} - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="md:flex">
        <!-- Sidebar (identique) -->
        <div class="bg-blue-800 text-white md:w-64">
            <div class="p-4">
                <h1 class="text-xl font-bold">
                    <i class="fas fa-hands-helping mr-2"></i>Aide EJP
                </h1>
                <p class="text-blue-200 text-sm">Bienvenue, {{ Auth::user()->name }}</p>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('aide.dashboard') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('aide.nouveaux.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.nouveaux.*') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-users mr-3"></i> Nouveaux
                </a>
            </nav>
            
            <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-blue-700">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </button>
            </form>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6">
            <!-- Navigation -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('aide.nouveaux.show', $nouveau) }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-2">
                            <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche
                        </a>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                            <i class="fas fa-history mr-2"></i>Historique de {{ $nouveau->full_name }}
                        </h2>
                        <div class="flex items-center text-gray-600 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($nouveau->statut['label'] === 'actif') bg-green-100 text-green-800
                                @elseif($nouveau->statut['label'] === 'moyen') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($nouveau->statut['label']) }} ({{ $nouveau->statut['pourcentage'] }}%)
                            </span>
                            <span class="mx-3 text-gray-300">•</span>
                            <span>{{ $participations->total() }} participations</span>
                        </div>
                    </div>
                    
                    <!-- Bouton export -->
                    <button onclick="exportHistorique()"
                            class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg">
                        <i class="fas fa-file-export mr-2"></i> Exporter
                    </button>
                </div>
            </div>
            
            <!-- Filtres avancés -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filtres avancés</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Période -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                        <select id="periode" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Toute période</option>
                            <option value="week">Cette semaine</option>
                            <option value="month">Ce mois</option>
                            <option value="quarter">Ce trimestre</option>
                            <option value="custom">Personnalisée</option>
                        </select>
                    </div>
                    
                    <!-- Date de début (caché par défaut) -->
                    <div id="dateDebutContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Du</label>
                        <input type="date" id="dateDebut" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Date de fin (caché par défaut) -->
                    <div id="dateFinContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Au</label>
                        <input type="date" id="dateFin" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Statut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select id="statutFilter" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Tous les statuts</option>
                            <option value="present">Présents uniquement</option>
                            <option value="absent">Absents uniquement</option>
                        </select>
                    </div>
                    
                    <!-- Type de programme -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de programme</label>
                        <select id="typeProgramme" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Tous les programmes</option>
                            <option value="culte">Culte</option>
                            <option value="etude">Étude biblique</option>
                            <option value="reunion">Réunion</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>
                
                <!-- Boutons filtres -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                    <button onclick="resetFilters()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-redo mr-2"></i> Réinitialiser
                    </button>
                    <button onclick="applyFilters()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Appliquer les filtres
                    </button>
                </div>
            </div>
            
            <!-- Statistiques des filtres -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-xl shadow text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $participations->total() }}</div>
                    <div class="text-gray-600">Total</div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow text-center border-l-4 border-green-500">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $nouveau->participations->where('present', true)->count() }}
                    </div>
                    <div class="text-gray-600">Présences</div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow text-center border-l-4 border-red-500">
                    <div class="text-2xl font-bold text-red-600">
                        {{ $nouveau->participations->where('present', false)->count() }}
                    </div>
                    <div class="text-gray-600">Absences</div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow text-center border-l-4 border-blue-500">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $nouveau->statut['pourcentage'] }}%
                    </div>
                    <div class="text-gray-600">Taux</div>
                </div>
            </div>
            
            <!-- Tableau des participations -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Programme
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date & Heure
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Motif d'absence
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Enregistré par
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($participations as $participation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">
                                            {{ $participation->programme->titre ?? 'Programme' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($participation->programme->description ?? '', 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $participation->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $participation->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if(str_contains(strtolower($participation->programme->titre ?? ''), 'culte')) bg-purple-100 text-purple-800
                                            @elseif(str_contains(strtolower($participation->programme->titre ?? ''), 'étude') || str_contains(strtolower($participation->programme->titre ?? ''), 'biblique')) bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $participation->programme->titre ? (str_contains(strtolower($participation->programme->titre), 'culte') ? 'Culte' : (str_contains(strtolower($participation->programme->titre), 'étude') ? 'Étude' : 'Autre')) : 'Autre' }}
                                        </span>
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
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                        {{ $participation->motif_absence ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Vous
                                        <div class="text-gray-400 text-xs">
                                            {{ $participation->enregistre_le->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editParticipation({{ $participation->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteParticipation({{ $participation->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-lg">Aucune participation</p>
                                        <p class="text-sm mt-2">Les participations apparaîtront ici</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($participations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $participations->links() }}
                    </div>
                @endif
            </div>
            
            <!-- Résumé et graphique -->
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Résumé mensuel -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar mr-2"></i>Résumé mensuel
                    </h3>
                    <div class="space-y-4">
                        @php
                            $currentMonth = now()->format('Y-m');
                            $monthParticipations = $nouveau->participations->filter(function($p) use ($currentMonth) {
                                return $p->created_at->format('Y-m') === $currentMonth;
                            });
                            $monthPresences = $monthParticipations->where('present', true)->count();
                            $monthAbsences = $monthParticipations->where('present', false)->count();
                            $monthTotal = $monthParticipations->count();
                            $monthRate = $monthTotal > 0 ? round(($monthPresences / $monthTotal) * 100, 1) : 0;
                        @endphp
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $monthTotal }}</div>
                                <div class="text-gray-600">Ce mois</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $monthPresences }}</div>
                                <div class="text-gray-600">Présences</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $monthAbsences }}</div>
                                <div class="text-gray-600">Absences</div>
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Taux ce mois: {{ $monthRate }}%</span>
                                <span>
                                    @if($monthRate >= 80)
                                        <span class="text-green-600 font-medium">Excellent</span>
                                    @elseif($monthRate >= 50)
                                        <span class="text-yellow-600 font-medium">Moyen</span>
                                    @else
                                        <span class="text-red-600 font-medium">À améliorer</span>
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full 
                                    @if($monthRate >= 80) bg-green-500
                                    @elseif($monthRate >= 50) bg-yellow-500
                                    @else bg-red-500 @endif" 
                                    style="width: {{ min($monthRate, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Évolution sur 6 mois (placeholder) -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-line mr-2"></i>Évolution (6 derniers mois)
                    </h3>
                    <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-chart-area text-3xl mb-3"></i>
                            <p>Graphique d'évolution</p>
                            <p class="text-sm">À implémenter avec Chart.js</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Gestion des filtres de période
        document.getElementById('periode').addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            document.getElementById('dateDebutContainer').classList.toggle('hidden', !isCustom);
            document.getElementById('dateFinContainer').classList.toggle('hidden', !isCustom);
        });
        
        // Initialiser les datepickers
        flatpickr("#dateDebut", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            maxDate: "today"
        });
        
        flatpickr("#dateFin", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            minDate: document.getElementById('dateDebut').value
        });
        
        // Mettre à jour la date min de fin quand début change
        document.getElementById('dateDebut').addEventListener('change', function() {
            flatpickr("#dateFin", {
                minDate: this.value
            });
        });
        
        function applyFilters() {
            const periode = document.getElementById('periode').value;
            const statut = document.getElementById('statutFilter').value;
            const type = document.getElementById('typeProgramme').value;
            
            let url = '?';
            
            if (periode !== 'all') url += `periode=${periode}&`;
            if (statut !== 'all') url += `statut=${statut}&`;
            if (type !== 'all') url += `type=${type}&`;
            
            if (periode === 'custom') {
                const debut = document.getElementById('dateDebut').value;
                const fin = document.getElementById('dateFin').value;
                if (debut) url += `debut=${debut}&`;
                if (fin) url += `fin=${fin}&`;
            }
            
            // Simuler le rechargement avec filtres
            alert('Application des filtres:\n' + url);
            // window.location.href = url;
        }
        
        function resetFilters() {
            document.getElementById('periode').value = 'all';
            document.getElementById('statutFilter').value = 'all';
            document.getElementById('typeProgramme').value = 'all';
            document.getElementById('dateDebutContainer').classList.add('hidden');
            document.getElementById('dateFinContainer').classList.add('hidden');
            
            // Recharger sans filtres
            // window.location.href = '?';
        }
        
        function exportHistorique() {
            alert('Export de l\'historique de {{ $nouveau->full_name }}\nFormat: PDF/Excel');
            // Ici, tu pourrais faire un appel AJAX pour générer un export
        }
        
        function editParticipation(id) {
            alert('Modification de la participation ' + id);
            // Ouvrir un modal de modification
        }
        
        function deleteParticipation(id) {
            if (confirm('Supprimer cette participation ?')) {
                alert('Suppression de la participation ' + id);
                // Ici, tu ferais un appel AJAX DELETE
            }
        }
    </script>
</body>
</html>