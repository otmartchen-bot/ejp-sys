<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .statut-actif { background-color: #10B981; color: white; }
        .statut-moyen { background-color: #F59E0B; color: white; }
        .statut-inactif { background-color: #EF4444; color: white; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="md:flex">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white md:w-64 min-h-screen">
            <div class="p-4">
                <h1 class="text-xl font-bold">
                    <i class="fas fa-hands-helping mr-2"></i>Aide EJP
                </h1>
                <p class="text-blue-200 text-sm">Bienvenue, {{ Auth::user()->name }}</p>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('aide.dashboard') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.dashboard') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('aide.nouveaux.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.nouveaux.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users mr-3"></i> Nouveaux
                </a>
                
                <!-- LIEN POUR CHANGER LE MOT DE PASSE (dans la sidebar seulement) -->
                <a href="{{ route('aide.profile.password') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.profile.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-key mr-3"></i> Changer mot de passe
                </a>
            </nav>
            
            <!-- Section utilisateur en bas -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-blue-700">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-400 to-orange-600 flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-300">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6">
            <!-- En-tête SIMPLE (tout à gauche) -->
            <div class="mb-6">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-home mr-2"></i>Accueil
                </h2>
                <p class="text-gray-600">Vos nouveaux assignés</p>
            </div>
            
            <!-- Bouton d'ajout rapide -->
            <div class="mb-6">
                <a href="{{ route('aide.nouveaux.create') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter un Nouveau
                </a>
            </div>
            
            <!-- Tableau simple -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom & Prénom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dernière participation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($nouveaux as $nouveau)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">
                                            {{ $nouveau->prenom }} {{ $nouveau->nom }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $nouveau->profession }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            // Calcule le statut
                                            $total = $nouveau->participations()->count();
                                            $presences = $nouveau->participations()->where('present', true)->count();
                                            
                                            if ($total === 0) {
                                                $statut = ['label' => 'inactif', 'pourcentage' => 0];
                                            } else {
                                                $taux = ($presences / $total) * 100;
                                                if ($taux >= 80) {
                                                    $statut = ['label' => 'actif', 'pourcentage' => round($taux, 1)];
                                                } elseif ($taux >= 50) {
                                                    $statut = ['label' => 'moyen', 'pourcentage' => round($taux, 1)];
                                                } else {
                                                    $statut = ['label' => 'inactif', 'pourcentage' => round($taux, 1)];
                                                }
                                            }
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                                            @if($statut['label'] === 'actif') statut-actif
                                            @elseif($statut['label'] === 'moyen') statut-moyen
                                            @else statut-inactif @endif">
                                            {{ ucfirst($statut['label']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $derniere = $nouveau->participations()->orderBy('created_at', 'desc')->first();
                                        @endphp
                                        @if($derniere)
                                            {{ $derniere->created_at->format('d/m/Y') }}
                                        @else
                                            Jamais
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('aide.nouveaux.details', $nouveau) }}" 
                                               class="text-blue-600 hover:text-blue-900"
                                               title="Détails">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <a href="{{ route('aide.participations.programmes', $nouveau) }}" 
                                               class="text-green-600 hover:text-green-900"
                                               title="Marquer présence">
                                                <i class="fas fa-calendar-check"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-lg">Aucun nouveau assigné</p>
                                        <p class="text-sm mt-2">Ajoutez votre premier nouveau pour commencer</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- NOTE : J'ai supprimé la div d'info en bas comme demandé -->
        </main>
    </div>
</body>
</html>