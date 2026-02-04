<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Système EJP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="md:flex">
        <aside class="md:w-64 bg-blue-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-church mr-2"></i>EJP Admin
                </h1>
                <p class="text-blue-200 text-sm mt-2">Système d'Intégration</p>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block py-3 px-6 hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('admin.aides.index') }}" 
                   class="block py-3 px-6 hover:bg-blue-800 {{ request()->routeIs('admin.aides.*') ? 'bg-blue-800 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-users mr-3"></i> Aides
                </a>
                <a href="{{ route('admin.programmes.index') }}" 
                   class="block py-3 px-6 hover:bg-blue-800 {{ request()->routeIs('admin.programmes.*') ? 'bg-blue-800 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-calendar-alt mr-3"></i> Programmes
                </a>
                <a href="{{ route('admin.nouveaux.index') }}" 
                   class="block py-3 px-6 hover:bg-blue-800 {{ request()->routeIs('admin.nouveaux.*') ? 'bg-blue-800 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-user-plus mr-3"></i> Nouveaux
                </a>
                <a href="{{ route('admin.statistiques.index') }}" 
                   class="block py-3 px-6 hover:bg-blue-800 {{ request()->routeIs('admin.statistiques.*') ? 'bg-blue-800 border-r-4 border-yellow-400' : '' }}">
                    <i class="fas fa-chart-bar mr-3"></i> Statistiques
                </a>
            </nav>
            
            <div class="p-6 border-t border-blue-800 mt-8">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-sm text-blue-300">Administrateur</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>
        
        <main class="flex-1 p-4 md:p-6">
            <div class="mb-6">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    @yield('title', 'Tableau de Bord')
                </h2>
                <p class="text-gray-600">@yield('subtitle', 'Gestion du système EJP')</p>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
</body>
</html>