<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marquer Présence - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .programme-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3B82F6;
        }
        .programme-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .programme-passe { border-left-color: #9CA3AF; opacity: 0.8; }
        .programme-en-cours { border-left-color: #10B981; }
        .programme-futur { border-left-color: #F59E0B; }
        
        /* NOUVEAUX STYLES POUR LES BOUTONS DE STATUT */
        .statut-btn {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-width: 2px;
        }
        
        .statut-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn-present {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            border-color: #10B981;
        }
        
        .btn-present:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        
        .btn-present.selected {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.4), 
                        inset 0 0 0 1px rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }
        
        .btn-absent {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
            border-color: #EF4444;
        }
        
        .btn-absent:hover {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }
        
        .btn-absent.selected {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.4), 
                        inset 0 0 0 1px rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }
        
        .statut-indicator {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }
        
        .statut-indicator.selected {
            opacity: 1;
            transform: scale(1);
        }
        
        .present-indicator {
            background-color: #10B981;
            color: white;
        }
        
        .absent-indicator {
            background-color: #EF4444;
            color: white;
        }
        
        /* Animation pour la sélection */
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        
        @keyframes pulse-glow-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        
        .selected.present-glow {
            animation: pulse-glow 0.5s ease;
        }
        
        .selected.absent-glow {
            animation: pulse-glow-red 0.5s ease;
        }
        
        .statut-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="md:flex">
        <!-- Sidebar -->
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
            <div class="mb-6">
                <a href="{{ route('aide.nouveaux.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux nouveaux
                </a>
                
                @if($nouveauSelectionne)
                    <!-- EN-TÊTE AVEC NOUVEAU PRÉ-SÉLECTIONNÉ -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <span class="font-bold text-blue-700 text-lg">
                                    {{ substr($nouveauSelectionne->prenom, 0, 1) }}{{ substr($nouveauSelectionne->nom, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                                    <i class="fas fa-calendar-check mr-2"></i>Marquer présence pour {{ $nouveauSelectionne->prenom }} {{ $nouveauSelectionne->nom }}
                                </h2>
                                <div class="flex items-center text-gray-600 mt-1">
                                    <span>{{ $nouveauSelectionne->profession }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $nouveauSelectionne->email }}</span>
                                    <span class="mx-2">•</span>
                                    @php
                                        $total = $nouveauSelectionne->participations()->count();
                                        $presences = $nouveauSelectionne->participations()->where('present', true)->count();
                                        $taux = $total > 0 ? round(($presences / $total) * 100, 1) : 0;
                                    @endphp
                                    <span class="font-medium {{ $taux >= 80 ? 'text-green-600' : ($taux >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $taux }}% de présence
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- EN-TÊTE SANS NOUVEAU PRÉ-SÉLECTIONNÉ (choix manuel) -->
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                        <i class="fas fa-calendar-check mr-2"></i>Marquer Présence/Absence
                    </h2>
                    <p class="text-gray-600">Sélectionnez d'abord un nouveau</p>
                    
                    <!-- Sélection manuelle du nouveau (seulement si pas pré-sélectionné) -->
                    <div class="mt-4 mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Choisir un nouveau :</label>
                        <select id="select-nouveau-manuel" class="w-full md:w-1/3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Sélectionner --</option>
                            @foreach($nouveaux as $nouveau)
                                <option value="{{ route('aide.participations.programmes', ['nouveau' => $nouveau->id]) }}">
                                    {{ $nouveau->prenom }} {{ $nouveau->nom }} - {{ $nouveau->profession }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            @if($nouveauSelectionne)
                <!-- Filtres pour les programmes -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Filtrer les programmes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                            <select id="filter-periode" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">Tous les programmes</option>
                                <option value="futur">À venir</option>
                                <option value="passe">Passés</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                            <input type="text" id="filter-recherche" 
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Nom du programme...">
                        </div>
                        
                        <div class="flex items-end">
                            <button onclick="appliquerFiltres()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-filter mr-2"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Liste des programmes -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Colonne des programmes (2/3 de l'écran) -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Programmes disponibles</h3>
                        
                        @if($programmes->isEmpty())
                            <div class="text-center py-12 bg-white rounded-xl shadow">
                                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun programme</h3>
                                <p class="text-gray-500">Aucun programme disponible pour le moment</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($programmes as $programme)
                                    @php
                                        $now = now();
                                        $dateProgramme = $programme->date_programme;
                                        $isPasse = $dateProgramme < $now;
                                        $isToday = $dateProgramme->format('Y-m-d') === $now->format('Y-m-d');
                                        $isFutur = $dateProgramme > $now;
                                        
                                        if ($isToday) {
                                            $cardClass = 'programme-en-cours';
                                            $badgeText = "Aujourd'hui";
                                            $badgeColor = 'bg-green-100 text-green-800';
                                        } elseif ($isPasse) {
                                            $cardClass = 'programme-passe';
                                            $badgeText = 'Passé';
                                            $badgeColor = 'bg-gray-100 text-gray-800';
                                        } else {
                                            $cardClass = 'programme-futur';
                                            $badgeText = 'À venir';
                                            $badgeColor = 'bg-yellow-100 text-yellow-800';
                                        }
                                    @endphp
                                    
                                    <div class="programme-card {{ $cardClass }} bg-white rounded-xl shadow p-5">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <h4 class="font-bold text-gray-800 text-lg">{{ $programme->titre }}</h4>
                                                    <span class="ml-3 px-2 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                                        {{ $badgeText }}
                                                    </span>
                                                </div>
                                                
                                                <div class="space-y-2">
                                                    <div class="flex items-center text-gray-600">
                                                        <i class="fas fa-calendar-day mr-2"></i>
                                                        <span>{{ $programme->date_programme->format('d/m/Y') }}</span>
                                                        <span class="mx-2">•</span>
                                                        <i class="fas fa-clock mr-2"></i>
                                                        <span>{{ $programme->date_programme->format('H:i') }}</span>
                                                    </div>
                                                    
                                                    @if($programme->lieu)
                                                        <div class="flex items-center text-gray-600">
                                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                                            <span>{{ $programme->lieu }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($programme->description)
                                                        <p class="text-gray-700 mt-2">{{ Str::limit($programme->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="ml-4">
                                                <button onclick="selectionnerProgramme({{ $programme->id }}, '{{ $programme->titre }}', '{{ $programme->date_programme->format('d/m/Y H:i') }}')"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                    <i class="fas fa-check mr-2"></i> Sélectionner
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            @if($programmes->hasPages())
                                <div class="mt-6">
                                    {{ $programmes->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Colonne du formulaire de présence (1/3 de l'écran) -->
                    <div>
                        <div class="sticky top-6">
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-6">
                                    <i class="fas fa-user-check mr-2"></i>Formulaire de présence
                                </h3>
                                
                                <!-- Programme sélectionné -->
                                <div id="programme-selectionne" class="hidden mb-6 p-4 bg-blue-50 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 id="programme-titre" class="font-bold text-blue-800 text-lg"></h4>
                                            <div class="mt-2 text-blue-700">
                                                <i class="fas fa-calendar-day mr-2"></i>
                                                <span id="programme-date"></span>
                                            </div>
                                        </div>
                                        <button onclick="deselectionnerProgramme()" class="text-blue-400 hover:text-blue-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Nouveau (affiché seulement) -->
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="font-bold text-blue-700">
                                                {{ substr($nouveauSelectionne->prenom, 0, 1) }}{{ substr($nouveauSelectionne->nom, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $nouveauSelectionne->prenom }} {{ $nouveauSelectionne->nom }}</div>
                                            <div class="text-sm text-gray-600">{{ $nouveauSelectionne->profession }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Statut présence/absence - VERSION AMÉLIORÉE AVEC COULEURS -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-4">
                                        <i class="fas fa-clipboard-check mr-2"></i>Statut de participation
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Bouton PRÉSENT -->
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="presence" value="present" class="sr-only peer" checked>
                                            <div class="statut-btn btn-present p-4 rounded-xl text-center">
                                                <i class="fas fa-check-circle statut-icon"></i>
                                                <div class="font-bold text-white text-lg">Présent</div>
                                                <div class="text-white text-sm opacity-90">Participation confirmée</div>
                                            </div>
                                            <!-- Indicateur de sélection -->
                                            <div class="statut-indicator present-indicator">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </label>
                                        
                                        <!-- Bouton ABSENT -->
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="presence" value="absent" class="sr-only peer">
                                            <div class="statut-btn btn-absent p-4 rounded-xl text-center">
                                                <i class="fas fa-times-circle statut-icon"></i>
                                                <div class="font-bold text-white text-lg">Absent</div>
                                                <div class="text-white text-sm opacity-90">Participation manquée</div>
                                            </div>
                                            <!-- Indicateur de sélection -->
                                            <div class="statut-indicator absent-indicator">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Légende -->
                                    <div class="mt-4 flex flex-wrap gap-3 text-xs">
                                        <div class="flex items-center bg-green-50 px-3 py-1.5 rounded-lg">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-green-700 font-medium">Présent = Participation confirmée</span>
                                        </div>
                                        <div class="flex items-center bg-red-50 px-3 py-1.5 rounded-lg">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                            <span class="text-red-700 font-medium">Absent = Remplir le motif</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Motif d'absence -->
                                <div id="motif-container" class="hidden mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-comment-alt mr-2"></i>Motif d'absence
                                    </label>
                                    <textarea id="motif-absence" rows="3" 
                                              class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Raison de l'absence (optionnel)"></textarea>
                                    <p class="text-gray-500 text-sm mt-1">Visible par l'administration</p>
                                </div>
                                
                                <!-- Date d'enregistrement (automatique) -->
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt text-gray-500 mr-3"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Date d'enregistrement</div>
                                            <div class="text-gray-900 font-bold" id="date-enregistrement">
                                                {{ now()->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-gray-500 text-sm">Automatique</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Bouton d'enregistrement -->
                                <div class="pt-4 border-t">
                                    <button onclick="enregistrerPresence()" 
                                            class="w-full py-3.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                                            id="btn-enregistrer" disabled>
                                        <i class="fas fa-save mr-3"></i> Enregistrer la participation
                                    </button>
                                    <p class="text-gray-500 text-sm mt-3 text-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Données envoyées à l'administration
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
    
    <script>
        // Variables globales
        let programmeSelectionne = null;
        const nouveauId = {{ $nouveauSelectionne->id ?? 'null' }};
        
        // Gestion améliorée des boutons de statut
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="presence"]');
            const statutContainers = document.querySelectorAll('.statut-btn');
            const indicators = document.querySelectorAll('.statut-indicator');
            
            // Initialiser l'état sélectionné
            updateStatutSelection();
            
            // Ajouter les événements
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateStatutSelection();
                    
                    // Afficher/masquer motif d'absence
                    const motifContainer = document.getElementById('motif-container');
                    motifContainer.classList.toggle('hidden', this.value !== 'absent');
                    
                    // Animation de sélection
                    const parent = this.closest('label');
                    if (parent) {
                        const statutDiv = parent.querySelector('.statut-btn');
                        if (this.value === 'present') {
                            statutDiv.classList.add('present-glow');
                            setTimeout(() => statutDiv.classList.remove('present-glow'), 500);
                        } else {
                            statutDiv.classList.add('absent-glow');
                            setTimeout(() => statutDiv.classList.remove('absent-glow'), 500);
                        }
                    }
                });
            });
            
            function updateStatutSelection() {
                // Réinitialiser toutes les sélections
                statutContainers.forEach(container => {
                    container.classList.remove('selected');
                });
                indicators.forEach(indicator => {
                    indicator.classList.remove('selected');
                });
                
                // Appliquer la sélection
                const checkedRadio = document.querySelector('input[name="presence"]:checked');
                if (checkedRadio) {
                    const parent = checkedRadio.closest('label');
                    if (parent) {
                        const statutDiv = parent.querySelector('.statut-btn');
                        const indicator = parent.querySelector('.statut-indicator');
                        if (statutDiv) statutDiv.classList.add('selected');
                        if (indicator) indicator.classList.add('selected');
                    }
                }
            }
        });
        
        // Redirection si sélection manuelle
        @if(!$nouveauSelectionne)
        document.getElementById('select-nouveau-manuel').addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });
        @endif
        
        // Sélectionner un programme
        function selectionnerProgramme(id, titre, date) {
            programmeSelectionne = id;
            
            // Afficher le programme sélectionné
            document.getElementById('programme-selectionne').classList.remove('hidden');
            document.getElementById('programme-titre').textContent = titre;
            document.getElementById('programme-date').textContent = date;
            
            // Activer le bouton d'enregistrement
            document.getElementById('btn-enregistrer').disabled = false;
            
            // Animation du bouton
            const btn = document.getElementById('btn-enregistrer');
            btn.classList.add('animate-pulse');
            setTimeout(() => btn.classList.remove('animate-pulse'), 1000);
            
            // Scroller vers le formulaire
            document.querySelector('.sticky').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Désélectionner un programme
        function deselectionnerProgramme() {
            programmeSelectionne = null;
            document.getElementById('programme-selectionne').classList.add('hidden');
            document.getElementById('btn-enregistrer').disabled = true;
        }
        
        // Enregistrer la participation
        function enregistrerPresence() {
            if (!programmeSelectionne || !nouveauId) {
                alert('Veuillez sélectionner un programme');
                return;
            }
            
            const presence = document.querySelector('input[name="presence"]:checked').value;
            const motif = document.getElementById('motif-absence').value;
            
            // Mettre à jour la date d'enregistrement
            const maintenant = new Date();
            const dateFormatee = `${maintenant.getDate().toString().padStart(2, '0')}/${(maintenant.getMonth()+1).toString().padStart(2, '0')}/${maintenant.getFullYear()} ${maintenant.getHours().toString().padStart(2, '0')}:${maintenant.getMinutes().toString().padStart(2, '0')}`;
            document.getElementById('date-enregistrement').textContent = dateFormatee;
            
            // Désactiver le bouton pendant l'envoi
            const btn = document.getElementById('btn-enregistrer');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> Enregistrement...';
            
            // Envoyer les données
            fetch("{{ route('aide.participations.enregistrer') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nouveau_id: nouveauId,
                    programme_id: programmeSelectionne,
                    present: presence === 'present',
                    motif_absence: presence === 'absent' ? motif : null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Succès avec animation
                    btn.innerHTML = '<i class="fas fa-check mr-3"></i> Succès !';
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        alert('✅ Participation enregistrée avec succès !');
                        
                        // Réinitialiser le formulaire
                        deselectionnerProgramme();
                        document.getElementById('motif-absence').value = '';
                        document.querySelector('input[name="presence"][value="present"]').checked = true;
                        document.getElementById('motif-container').classList.add('hidden');
                        
                        // Recharger la page après 1.5 secondes
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                        
                    }, 500);
                    
                } else {
                    // Erreur
                    btn.innerHTML = '<i class="fas fa-exclamation-triangle mr-3"></i> Erreur';
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-red-600');
                    
                    setTimeout(() => {
                        alert('❌ Erreur: ' + (data.message || 'Échec de l\'enregistrement'));
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save mr-3"></i> Enregistrer';
                        btn.classList.remove('bg-red-600');
                        btn.classList.add('bg-blue-600');
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                
                btn.innerHTML = '<i class="fas fa-exclamation-triangle mr-3"></i> Erreur réseau';
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-red-600');
                
                setTimeout(() => {
                    alert('❌ Erreur réseau. Vérifiez votre connexion.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-3"></i> Enregistrer';
                    btn.classList.remove('bg-red-600');
                    btn.classList.add('bg-blue-600');
                }, 500);
            });
        }
        
        // Appliquer les filtres (simulation)
        function appliquerFiltres() {
            const periode = document.getElementById('filter-periode').value;
            const recherche = document.getElementById('filter-recherche').value;
            
            // Simuler le filtrage
            console.log(`Filtres: Période=${periode}, Recherche=${recherche}`);
            // En réalité, tu ferais une requête AJAX
        }
        
        // Initialiser l'heure en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                const maintenant = new Date();
                const dateFormatee = `${maintenant.getDate().toString().padStart(2, '0')}/${(maintenant.getMonth()+1).toString().padStart(2, '0')}/${maintenant.getFullYear()} ${maintenant.getHours().toString().padStart(2, '0')}:${maintenant.getMinutes().toString().padStart(2, '0')}`;
                document.getElementById('date-enregistrement').textContent = dateFormatee;
            }, 60000);
        });
    </script>
</body>
</html>