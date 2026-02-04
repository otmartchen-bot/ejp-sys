<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participations - Aide EJP</title>
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
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.nouveaux.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users mr-3"></i> Mes Nouveaux
                </a>
                <a href="{{ route('aide.participations.programmes') }}" 
                   class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('aide.participations.*') ? 'bg-blue-700 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-calendar-check mr-3"></i> Présences
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
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-history mr-2"></i>Historique des Participations
                </h2>
                <p class="text-gray-600">Consultez l'historique complet des présences/absences</p>
            </div>
            
            <div class="text-center py-12 bg-white rounded-xl shadow">
                <i class="fas fa-calendar-check text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Page en construction</h3>
                <p class="text-gray-500 mb-6">Cette fonctionnalité sera disponible prochainement.</p>
                <a href="{{ route('aide.participations.programmes') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux programmes
                </a>
            </div>
        </main>
    </div>
</body>
</html>