<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Nouveau - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-user-plus mr-2"></i>Ajouter un Nouveau
                </h2>
                <p class="text-gray-600">Remplissez les informations du nouveau membre</p>
            </div>
            
            <!-- Formulaire -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form action="{{ route('aide.nouveaux.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne gauche -->
                        <div class="space-y-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Dupont">
                                @error('nom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="prenom" name="prenom" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Jean">
                                @error('prenom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="jean.dupont@email.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Colonne droite -->
                        <div class="space-y-6">
                            <!-- Profession -->
                            <div>
                                <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                                    Profession <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="profession" name="profession" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Étudiant, Ingénieur, etc.">
                                @error('profession')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- FIJ -->
                            <div>
    <label for="fij" class="block text-sm font-medium text-gray-700 mb-2">
        Famille d'Impact Jeune (FIJ) <span class="text-red-500">*</span>
    </label>
    <input type="text" id="fij" name="fij" required
           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
           placeholder="Ex: Famille Jean, Famille Marie, etc.">
    <p class="text-gray-500 text-xs mt-1">Nom de la famille d'impact jeune</p>
    @error('fij')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
                            
                            <!-- Date d'enregistrement -->
                            <div>
                                <label for="date_enregistrement" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date d'enregistrement <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_enregistrement" name="date_enregistrement" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ date('Y-m-d') }}">
                                @error('date_enregistrement')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons -->
                    <div class="flex justify-end space-x-3 pt-8 mt-8 border-t">
                        <a href="{{ route('aide.nouveaux.index') }}" 
                           class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Instructions -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-blue-800 mb-2">Informations importantes</h4>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li>• Tous les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires</li>
                            <li>• L'email doit être unique pour chaque nouveau</li>
                            <li>• La FIJ correspond à la famille d'impact jeune</li>
                            <li>• Vous pourrez modifier ces informations ultérieurement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Date par défaut = aujourd'hui
        document.getElementById('date_enregistrement').valueAsDate = new Date();
    </script>
</body>
</html>