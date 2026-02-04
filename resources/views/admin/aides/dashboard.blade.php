<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .statut-actif { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .statut-moyen { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
        .statut-inactif { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }
        .hover-scale:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen">
    
    <!-- Sidebar Mobile -->
    <div class="md:hidden">
        <div class="bg-blue-800 text-white p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-700 flex items-center justify-center mr-3">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div>
                        <h1 class="font-bold">Aide EJP</h1>
                        <p class="text-xs text-blue-200">{{ $user->name }}</p>
                    </div>
                </div>
                <button id="menuToggle" class="text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Menu mobile (caché par défaut) -->
        <div id="mobileMenu" class="hidden bg-blue-900 text-white">
            <nav class="py-4">
                <a href="{{ route('aide.dashboard') }}" class="block py-3 px-6 hover:bg-blue-800">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('aide.nouveaux.index') }}" class="block py-3 px-6 hover:bg-blue-800">
                    <i class="fas fa-users mr-3"></i> Mes Nouveaux
                </a>
                <a href="{{ route('aide.participations.programmes') }}" class="block py-3 px-6 hover:bg-blue-800">
                    <i class="fas fa-calendar-check mr-3"></i> Marquer Présences
                </a>
            </nav>
        </div>
    </div>

    <div class="md:flex">
        <!-- Sidebar Desktop -->
        <div class="hidden md:block md:w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-hands-helping mr-2"></i>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                        Aide EJP
                    </span>
                </h1>
                <p class="text-blue-200 text-sm mt-2">Bienvenue, {{ $user->name }}</p>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('aide.dashboard') }}" 
                   class="block py-4 px-6 hover:bg-blue-700/50 border-l-4 border-transparent
                          {{ request()->routeIs('aide.dashboard') ? 'bg-blue-700/30 border-yellow-400' : '' }}
                          transition-all duration-200">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('aide.nouveaux.index') }}" 
                   class="block py-4 px-6 hover:bg-blue-700/50 border-l-4 border-transparent
                          {{ request()->routeIs('aide.nouveaux.*') ? 'bg-blue-700/30 border-yellow-400' : '' }}
                          transition-all duration-200">
                    <i class="fas fa-users mr-3"></i> Mes Nouveaux
                </a>
                <a href="{{ route('aide.participations.programmes') }}" 
                   class="block py-4 px-6 hover:bg-blue-700/50 border-l-4 border-transparent
                          {{ request()->routeIs('aide.participations.*') ? 'bg-blue-700/30 border-yellow-400' : '' }}
                          transition-all duration-200">
                    <i class="fas fa-calendar-check mr-3"></i> Marquer Présences
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 px-4 
                                   rounded-lg font-medium hover:from-red-700 hover:to-red-800 
                                   transition-all duration-300 transform hover:-translate-y-0.5 
                                   shadow-lg hover:shadow-xl flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6">
            
            <!-- Header avec bouton rapide -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
                            Tableau de Bord
                        </h1>
                        <p class="text-gray-600">Vue d'ensemble de vos nouveaux</p>
                    </div>
                    <a href="{{ route('aide.nouveaux.create') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 
                              text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 
                              shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter un Nouveau
                    </a>
                </div>
                
                <!-- Cartes de statistiques -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-scale">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-xl">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Nouveaux</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_nouveaux'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-scale border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-xl">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Actifs</p>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['actifs'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-scale border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-xl">
                                <i class="fas fa-exclamation-circle text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Moyens</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $stats['moyens'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-scale border-l-4 border-red-500">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-xl">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Inactifs</p>
                                <p class="text-2xl font-bold text-red-600">{{ $stats['inactifs'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deux colonnes principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Colonne gauche : Nouveaux par statut -->
                <div class="space-y-6">
                    <!-- Actifs -->
                    @php $actifs = $nouveaux->where('statut_calculated.label', 'actif'); @endphp
                    @if($actifs->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-green-200">
                        <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                    <h3 class="font-bold text-lg">Actifs ({{ $actifs->count() }})</h3>
                                </div>
                                <span class="text-green-700 font-medium">
                                    {{ round($actifs->avg(fn($n) => $n->statut_calculated['pourcentage'])) }}% moyen
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($actifs as $nouveau)
                                <div class="flex items-center justify-between p-4 rounded-lg bg-green-50/50 hover:bg-green-50 transition-colors">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $nouveau->prenom }} {{ $nouveau->nom }}</h4>
                                        <p class="text-sm text-gray-600">{{ $nouveau->profession }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $nouveau->statut_calculated['pourcentage'] }}%
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('aide.participations.marquer', ['nouveau' => $nouveau->id, 'programme' => 'current']) }}"
                                               class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-check-circle mr-1"></i> Présence
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Moyens -->
                    @php $moyens = $nouveaux->where('statut_calculated.label', 'moyen'); @endphp
                    @if($moyens->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-yellow-200">
                        <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></div>
                                    <h3 class="font-bold text-lg">Moyens ({{ $moyens->count() }})</h3>
                                </div>
                                <span class="text-yellow-700 font-medium">
                                    {{ round($moyens->avg(fn($n) => $n->statut_calculated['pourcentage'])) }}% moyen
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($moyens as $nouveau)
                                <div class="flex items-center justify-between p-4 rounded-lg bg-yellow-50/50 hover:bg-yellow-50">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $nouveau->prenom }} {{ $nouveau->nom }}</h4>
                                        <p class="text-sm text-gray-600">{{ $nouveau->profession }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $nouveau->statut_calculated['pourcentage'] }}%
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('aide.participations.marquer', ['nouveau' => $nouveau->id, 'programme' => 'current']) }}"
                                               class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Suivre
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Inactifs -->
                    @php $inactifs = $nouveaux->where('statut_calculated.label', 'inactif'); @endphp
                    @if($inactifs->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-200">
                        <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                                    <h3 class="font-bold text-lg">Inactifs ({{ $inactifs->count() }})</h3>
                                </div>
                                <span class="text-red-700 font-medium">
                                    {{ round($inactifs->avg(fn($n) => $n->statut_calculated['pourcentage'])) }}% moyen
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($inactifs as $nouveau)
                                <div class="flex items-center justify-between p-4 rounded-lg bg-red-50/50 hover:bg-red-50">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $nouveau->prenom }} {{ $nouveau->nom }}</h4>
                                        <p class="text-sm text-gray-600">{{ $nouveau->profession }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $nouveau->statut_calculated['pourcentage'] }}%
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('aide.nouveaux.show', $nouveau) }}"
                                               class="text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-user-clock mr-1"></i> Contacter
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Colonne droite : Programmes et actions -->
                <div class="space-y-8">
                    
                    <!-- Programmes à venir -->
                    <div class="bg-white rounded-2xl shadow-lg">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                                Programmes à venir
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($programmes->count() > 0)
                                <div class="space-y-4">
                                    @foreach($programmes as $programme)
                                    <div class="p-4 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $programme->titre }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ $programme->date_programme->format('d/m/Y à H:i') }}
                                                </p>
                                                @if($programme->description)
                                                <p class="text-sm text-gray-500 mt-2">
                                                    {{ Str::limit($programme->description, 80) }}
                                                </p>
                                                @endif
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                @if($programme->date_programme->isToday()) bg-green-100 text-green-800
                                                @elseif($programme->date_programme->isPast()) bg-gray-100 text-gray-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                @if($programme->date_programme->isToday()) Aujourd'hui
                                                @elseif($programme->date_programme->isPast()) Passé
                                                @else À venir @endif
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('aide.participations.programmes') }}?programme={{ $programme->id }}"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-check-square mr-2"></i>
                                                Marquer les présences
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">Aucun programme à venir</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions rapides -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-bold flex items-center">
                                <i class="fas fa-bolt mr-2"></i>
                                Actions rapides
                            </h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="{{ route('aide.nouveaux.create') }}"
                                   class="bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-all duration-200 
                                          border border-white/20 hover:border-white/40 group">
                                    <div class="flex items-center">
                                        <div class="bg-white/20 p-3 rounded-lg group-hover:bg-white/30">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium">Ajouter Nouveau</p>
                                            <p class="text-sm text-blue-200">Enregistrement rapide</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a href="{{ route('aide.participations.programmes') }}"
                                   class="bg-white/10 hover:bg-white/20 p-4 rounded-xl transition-all duration-200 
                                          border border-white/20 hover:border-white/40 group">
                                    <div class="flex items-center">
                                        <div class="bg-white/20 p-3 rounded-lg group-hover:bg-white/30">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium">Marquer Présences</p>
                                            <p class="text-sm text-blue-200">Pour tous les programmes</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiques hebdomadaires -->
                    <div class="bg-white rounded-2xl shadow-lg">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-bold text-gray-900">Cette semaine</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-blue-600 mb-2">
                                    {{ $stats['participations_semaine'] }}
                                </div>
                                <p class="text-gray-600">Participations enregistrées</p>
                                <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: {{ min(100, ($stats['participations_semaine'] / max(1, $stats['total_nouveaux'])) * 100) }}%">
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ round(($stats['participations_semaine'] / max(1, $stats['total_nouveaux'])) * 100) }}% de vos nouveaux
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Message si pas de nouveaux -->
            @if($nouveaux->count() === 0)
            <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <i class="fas fa-users text-yellow-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun nouveau assigné</h3>
                <p class="text-gray-600 mb-6">Commencez par ajouter des nouveaux à suivre</p>
                <a href="{{ route('aide.nouveaux.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 
                          text-white font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 
                          shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter votre premier nouveau
                </a>
            </div>
            @endif
            
        </main>
    </div>
    
    <!-- Script pour menu mobile -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menu mobile toggle
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Effet hover sur les cartes
        const cards = document.querySelectorAll('.hover-scale');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
        
        // Animation pour l'apparition
        const elements = document.querySelectorAll('.bg-white');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
    </script>
    
</body>
</html>