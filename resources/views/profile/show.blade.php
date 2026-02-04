<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - EJP</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    @auth
        @if(auth()->user()->isAide())
            @include('aide.layouts.navigation')
        @elseif(auth()->user()->isAdmin())
            @include('admin.layouts.navigation')
        @endif
    @endauth
    
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Carte principale -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        <i class="fas fa-user-circle mr-3 text-orange-600"></i>
                        Mon Profil
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Gérer vos informations personnelles et votre sécurité
                    </p>
                </div>
                
                <!-- Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                
                <!-- Informations profil -->
                <div class="mb-10">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informations personnelles
                    </h2>
                    
                    <form method="POST" action="{{ route('aide.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ auth()->user()->name }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       required>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ auth()->user()->email }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Bouton sauvegarder -->
                        <div class="mt-6">
                            <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                Sauvegarder les modifications
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Section sécurité -->
                <div class="border-t pt-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-shield-alt mr-2 text-red-500"></i>
                        Sécurité du compte
                    </h2>
                    
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-key text-red-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-red-800">
                                    Mot de passe
                                </h3>
                                <p class="mt-1 text-red-700">
                                    Pour votre sécurité, il est recommandé de changer votre mot de passe régulièrement.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('aide.profile.password') }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                                        <i class="fas fa-key mr-2"></i>
                                        Changer mon mot de passe
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Information compte -->
                <div class="mt-8 pt-8 border-t">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-user-tag mr-2"></i>
                        Informations du compte
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Rôle</p>
                            <p class="font-medium text-gray-800">
                                {{ auth()->user()->role === 'aide' ? 'Aide à l\'intégration' : 'Administrateur' }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Date de création</p>
                            <p class="font-medium text-gray-800">
                                {{ auth()->user()->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>