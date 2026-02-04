<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer mot de passe - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-save {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        .btn-cancel {
            background: white;
            border: 2px solid #E5E7EB;
            color: #4B5563;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }
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
                   class="block py-3 px-4 hover:bg-blue-700">
                    <i class="fas fa-home mr-3"></i> Accueil
                </a>
                <a href="{{ route('aide.nouveaux.index') }}" 
                   class="block py-3 px-4 hover:bg-blue-700">
                    <i class="fas fa-users mr-3"></i> Nouveaux
                </a>
                
                <!-- LIEN ACTIF (page courante) -->
                <a href="{{ route('aide.profile.password') }}" 
                   class="block py-3 px-4 bg-blue-700 border-r-4 border-yellow-400">
                    <i class="fas fa-key mr-3"></i> Changer mot de passe
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-4 border-t border-blue-700">
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
            <div class="max-w-2xl mx-auto">
                <!-- En-tête -->
                <div class="mb-8">
                    <a href="{{ route('aide.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        <i class="fas fa-key mr-3 text-green-600"></i>
                        Changer mon mot de passe
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Mettez à jour votre mot de passe pour sécuriser votre compte.
                    </p>
                </div>
                
                <!-- Messages -->
                @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                @endif
                
                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <div>
                            <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="list-disc list-inside text-sm mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Formulaire -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <form method="POST" action="{{ route('aide.profile.password.update') }}">
                        @csrf
                        
                        <!-- Mot de passe actuel -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-500"></i>
                                Mot de passe actuel
                            </label>
                            <div class="relative">
                                <input id="current_password" 
                                       name="current_password" 
                                       type="password" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Entrez votre mot de passe actuel">
                                <button type="button" 
                                        onclick="togglePassword('current_password')"
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Nouveau mot de passe -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-gray-500"></i>
                                Nouveau mot de passe
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Minimum 8 caractères">
                                <button type="button" 
                                        onclick="togglePassword('password')"
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Utilisez au moins 8 caractères avec des lettres, chiffres et symboles.
                            </p>
                        </div>
                        
                        <!-- Confirmation -->
                        <div class="mb-8">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-gray-500"></i>
                                Confirmer le nouveau mot de passe
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Retapez le nouveau mot de passe">
                                <button type="button" 
                                        onclick="togglePassword('password_confirmation')"
                                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- BOUTONS D'ACTION -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- BOUTON ENREGISTRER (AJOUTÉ) -->
                            <button type="submit"
                                    class="btn-save py-3 px-6 rounded-lg flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer les modifications
                            </button>
                            
                            <!-- BOUTON ANNULER -->
                            <a href="{{ route('aide.dashboard') }}" 
                               class="btn-cancel py-3 px-6 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Note de sécurité -->
                <div class="mt-8 p-5 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">Sécurité du compte</h3>
                            <ul class="mt-2 text-blue-700 space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                                    <span>Votre mot de passe est crypté et sécurisé</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                                    <span>Changez votre mot de passe tous les 3 mois</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                                    <span>Ne partagez jamais vos identifiants</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
        
        // Animation pour le bouton Enregistrer
        document.addEventListener('DOMContentLoaded', function() {
            const saveBtn = document.querySelector('.btn-save');
            if (saveBtn) {
                saveBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                saveBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }
        });
    </script>
</body>
</html>