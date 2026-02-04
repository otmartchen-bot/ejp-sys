<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier - {{ $nouveau->full_name }} - Aide EJP</title>
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
                <a href="{{ route('aide.nouveaux.details', $nouveau) }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
                </a>
                
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                            <i class="fas fa-edit mr-2"></i>Modifier {{ $nouveau->full_name }}
                        </h2>
                        <p class="text-gray-600">Mettez à jour les informations du nouveau</p>
                    </div>
                    
                    <!-- Statut badge -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($nouveau->statut['label'] === 'actif') bg-green-100 text-green-800
                        @elseif($nouveau->statut['label'] === 'moyen') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($nouveau->statut['label']) }} ({{ $nouveau->statut['pourcentage'] }}%)
                    </span>
                </div>
            </div>
            
            <!-- Formulaire de modification -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form action="{{ route('aide.nouveaux.update', $nouveau) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne gauche -->
                        <div class="space-y-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom', $nouveau->nom) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @error('nom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $nouveau->prenom) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @error('prenom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $nouveau->email) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                                <input type="text" id="profession" name="profession" value="{{ old('profession', $nouveau->profession) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @error('profession')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- FIJ -->
                            <div>
                                <label for="fij" class="block text-sm font-medium text-gray-700 mb-2">
                                    Famille d'Impact Jeune (FIJ) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="fij" name="fij" value="{{ old('fij', $nouveau->fij) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ex: Famille Jean, Famille Marie, etc.">
                                <p class="text-gray-500 text-xs mt-1">Nom de la famille d'impact du jeune</p>
                                @error('fij')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Date d'enregistrement (non modifiable) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Date d'enregistrement
                                </label>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($nouveau->date_enregistrement)->format('d/m/Y') }}</p>
                                    <p class="text-gray-500 text-sm mt-1">Non modifiable</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons -->
                    <div class="flex justify-between pt-8 mt-8 border-t">
                        <div>
                            <button type="button" onclick="confirmDelete()"
                                    class="px-5 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 inline-flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i> Supprimer
                            </button>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('aide.nouveaux.details', $nouveau) }}" 
                               class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- NOTE: Section des statistiques SUPPRIMÉE comme demandé -->
        </main>
    </div>
    
    <!-- Modal de confirmation suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Supprimer ce nouveau ?</h3>
                    
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-red-500 text-2xl mr-3"></i>
                            <div>
                                <div class="font-bold text-red-800">{{ $nouveau->full_name }}</div>
                                <div class="text-red-700 text-sm">{{ $nouveau->email }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mt-4">
                        Cette action supprimera définitivement ce nouveau et toutes ses participations.
                        <span class="font-bold text-red-600">Cette action est irréversible.</span>
                    </p>
                </div>
                
                <div class="mt-8 flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <form action="{{ route('aide.nouveaux.destroy', $nouveau) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 inline-flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        // Empêcher la fermeture accidentelle
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>