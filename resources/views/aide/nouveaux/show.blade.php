<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $nouveau->full_name }} - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="{{ route('aide.nouveaux.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="font-bold text-blue-700 text-2xl">
                                {{ substr($nouveau->prenom, 0, 1) }}{{ substr($nouveau->nom, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $nouveau->full_name }}</h2>
                            <div class="flex items-center text-gray-600 mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($nouveau->statut['label'] === 'actif') bg-green-100 text-green-800
                                    @elseif($nouveau->statut['label'] === 'moyen') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    {{ ucfirst($nouveau->statut['label']) }} ({{ $nouveau->statut['pourcentage'] }}%)
                                </span>
                                <span class="mx-3 text-gray-300">•</span>
                                <span>{{ $nouveau->profession }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 mt-4 md:mt-0">
                        <a href="{{ route('aide.nouveaux.edit', $nouveau) }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('aide.nouveaux.details', $nouveau) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg">
                            <i class="fas fa-info-circle mr-2"></i> Détails complets
                        </a>
                        <button onclick="showPresenceModal()"
                                class="inline-flex items-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg">
                            <i class="fas fa-calendar-check mr-2"></i> Présence
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques principales -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl shadow text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $nouveau->participations->count() }}</div>
                    <div class="text-gray-600">Programmes</div>
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow text-center border-l-4 border-green-500">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['presences'] }}</div>
                    <div class="text-gray-600">Présences</div>
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow text-center border-l-4 border-red-500">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['absences'] }}</div>
                    <div class="text-gray-600">Absences</div>
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow text-center border-l-4 border-purple-500">
                    <div class="text-3xl font-bold text-purple-600">
                        {{ $stats['total_participations'] > 0 ? round(($stats['presences'] / $stats['total_participations']) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-gray-600">Taux présence</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Colonne gauche: Informations -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Dernières participations -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-history mr-2"></i>Dernières participations
                        </h3>
                        
                        @if($nouveau->participations->count() > 0)
                            <div class="space-y-4">
                                @foreach($nouveau->participations->sortByDesc('created_at')->take(5) as $participation)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-gray-800">{{ $participation->programme->titre ?? 'Programme' }}</h4>
                                                <div class="flex items-center text-gray-600 text-sm mt-1">
                                                    <i class="fas fa-calendar-day mr-2"></i>
                                                    <span>{{ $participation->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                @if(!$participation->present && $participation->motif_absence)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        <i class="fas fa-comment mr-1"></i>
                                                        {{ $participation->motif_absence }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                @if($participation->present) bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $participation->present ? 'Présent' : 'Absent' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($nouveau->participations->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('aide.nouveaux.historique', $nouveau) }}" 
                                       class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                        <i class="fas fa-list mr-2"></i> Voir tout l'historique
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>Aucune participation enregistrée</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Graphique de participation (placeholder) -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-line mr-2"></i>Évolution
                        </h3>
                        <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-chart-bar text-3xl mb-3"></i>
                                <p>Graphique des participations</p>
                                <p class="text-sm">Disponible prochainement</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne droite: Informations et actions -->
                <div class="space-y-6">
                    <!-- Carte informations -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>Informations
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-gray-900">{{ $nouveau->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Profession</label>
                                <p class="mt-1 text-gray-900">{{ $nouveau->profession }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">FIJ</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $nouveau->fij }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date d'enregistrement</label>
                                <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($nouveau->date_enregistrement)->format('d/m/Y') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Ajouté le</label>
                                <p class="mt-1 text-gray-900">{{ $nouveau->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t">
                            <a href="{{ route('aide.nouveaux.edit', $nouveau) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-edit mr-2"></i> Modifier les informations
                            </a>
                        </div>
                    </div>
                    
                    <!-- Actions rapides -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-bolt mr-2"></i>Actions rapides
                        </h3>
                        
                        <div class="space-y-3">
                            <button onclick="showPresenceModal()"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg">
                                <i class="fas fa-calendar-check mr-3"></i>
                                <div class="text-left">
                                    <div class="font-medium">Marquer présence</div>
                                    <div class="text-sm">Pour un programme</div>
                                </div>
                            </button>
                            
                            <a href="{{ route('aide.nouveaux.historique', $nouveau) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg">
                                <i class="fas fa-history mr-3"></i>
                                <div class="text-left">
                                    <div class="font-medium">Voir l'historique</div>
                                    <div class="text-sm">Toutes les participations</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('aide.nouveaux.details', $nouveau) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg">
                                <i class="fas fa-file-alt mr-3"></i>
                                <div class="text-left">
                                    <div class="font-medium">Fiche complète</div>
                                    <div class="text-sm">Détails + historique</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal présence (similaire aux autres) -->
    <div id="presenceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full">
            <!-- Contenu identique à details.blade.php -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Marquer présence pour {{ $nouveau->full_name }}</h3>
                    <button onclick="closePresenceModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Liste des programmes -->
                    @for($i = 1; $i <= 3; $i++)
                        <div class="border rounded-lg p-4 hover:border-blue-300 cursor-pointer" onclick="selectProgrammeShow({{ $i }})">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-gray-800">Programme {{ $i }}</h4>
                                    <p class="text-gray-600 text-sm">Date • Heure</p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <!-- Formulaire (caché par défaut) -->
                <div id="presenceFormShow" class="hidden mt-6 p-4 bg-gray-50 rounded-lg">
                    <!-- Formulaire identique à details.blade.php -->
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Scripts modaux similaires aux autres fichiers
        function showPresenceModal() {
            document.getElementById('presenceModal').classList.remove('hidden');
        }
        
        function closePresenceModal() {
            document.getElementById('presenceModal').classList.add('hidden');
        }
        
        function selectProgrammeShow(id) {
            // Logique similaire
            alert('Sélection du programme ' + id);
        }
    </script>
</body>
</html>