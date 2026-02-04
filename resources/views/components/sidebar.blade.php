@props(['active' => 'dashboard'])

<!-- Sidebar Glass -->
<aside class="sidebar-glass w-64 min-h-screen py-6 px-4 flex flex-col">
    <!-- Logo -->
    <div class="logo-container glass-card p-4 mb-8 flex items-center justify-center">
        <img src="/images/logo.png" alt="EJP" class="h-12 w-auto">
        <div class="ml-3">
            <h1 class="font-bold text-lg text-gray-800">EJP Système</h1>
            <p class="text-xs text-gray-600">{{ Auth::user()->role === 'admin' ? 'Administration' : 'Aide à l\'Intégration' }}</p>
        </div>
    </div>
    
    <!-- User info -->
    <div class="glass-card p-4 mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange to-green flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="ml-3">
                <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 space-y-2">
        @if(Auth::user()->role === 'admin')
            <!-- Menu Admin -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'dashboard' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-chart-pie mr-3"></i>
                Tableau de bord
            </a>
            
            <a href="{{ route('admin.aides.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'aides' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-users mr-3"></i>
                Gestion des Aides
            </a>
            
            <a href="{{ route('admin.nouveaux.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'nouveaux' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-user-plus mr-3"></i>
                Nouveaux
            </a>
            
            <a href="{{ route('admin.programmes.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'programmes' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-calendar-alt mr-3"></i>
                Programmes
            </a>
            
            <a href="{{ route('admin.statistiques.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'statistiques' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-chart-line mr-3"></i>
                Statistiques
            </a>
            
        @else
            <!-- Menu Aide -->
            <a href="{{ route('aide.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'dashboard' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-home mr-3"></i>
                Accueil
            </a>
            
            <a href="{{ route('aide.nouveaux.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'nouveaux' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-users mr-3"></i>
                Mes Nouveaux
            </a>
            
            <a href="{{ route('aide.participations.programmes') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ $active === 'presences' ? 'bg-gradient-to-r from-orange to-green text-white' : 'text-gray-700 hover:bg-white/50' }}">
                <i class="fas fa-calendar-check mr-3"></i>
                Marquer Présences
            </a>
        @endif
    </nav>
    
    <!-- Déconnexion -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-gray-700 to-gray-900 text-white hover:shadow-lg transition-all duration-300">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Déconnexion
            </button>
        </form>
    </div>
</aside>