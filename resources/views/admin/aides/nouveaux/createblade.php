<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Nouveau - Aide EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen p-4">
    
    <!-- Container principal -->
    <div class="max-w-4xl mx-auto">
        
        <!-- En-tête -->
        <div class="mb-8">
            <a href="{{ route('aide.nouveaux.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Ajouter un Nouveau</h1>
                    <p class="text-gray-600">Enregistrement d'un nouveau participant</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
            </div>
        </div>
        
        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Barre de progression -->
            <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600"></div>
            
            <form method="POST" action="{{ route('aide.nouveaux.store') }}" class="p-6 md:p-8">
                @csrf
                
                <div class="space-y-8">
                    
                    <!-- Section Informations de base -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Informations personnelles</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom *
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" name="prenom" id="prenom" required
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                                  focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                                  hover:border-blue-400"
                                           placeholder="Jean">
                                </div>
                                @error('prenom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom *
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <input type="text" name="nom" id="nom" required
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                                  focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                                  hover:border-blue-400"
                                           placeholder="Dupont">
                                </div>
                                @error('nom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-1 text-blue-500"></i> Email *
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-at"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                                  focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                                  hover:border-blue-400"
                                           placeholder="exemple@email.com">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Profession -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-briefcase text-green-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Informations professionnelles</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Profession -->
                            <div>
                                <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                                    Profession *
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <input type="text" name="profession" id="profession" required
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                                  focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                                  hover:border-blue-400"
                                           placeholder="Ex: Étudiant, Ingénieur...">
                                </div>
                                @error('profession')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- FIJ -->
                            <div>
                                <label for="fij" class="block text-sm font-medium text-gray-700 mb-2">
                                    FIJ *
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <input type="text" name="fij" id="fij" required
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                                  focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                                  hover:border-blue-400"
                                           placeholder="Code FIJ">
                                </div>
                                @error('fij')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Contact -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-purple-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Coordonnées</h2>
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 top-3 text-gray-400">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <input type="tel" name="telephone" id="telephone"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                                              focus:ring-blue-500 focus:border-blue-500 transition-all duration-200
                                              hover:border-blue-400"
                                       placeholder="06 12 34 56 78">
                            </div>
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optionnel</p>
                        </div>
                    </div>
                    
                    <!-- Informations complémentaires -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-info text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-blue-900">Informations</h3>
                                <ul class="mt-2 text-sm text-blue-700 space-y-1">
                                    <li><i class="fas fa-check-circle mr-2"></i> Les champs marqués d'un * sont obligatoires</li>
                                    <li><i class="fas fa-check-circle mr-2"></i> Le nouveau sera automatiquement assigné à votre compte</li>
                                    <li><i class="fas fa-check-circle mr-2"></i> Vous pourrez modifier ces informations plus tard</li>
                                    <li><i class="fas fa-check-circle mr-2"></i> Un email de confirmation sera envoyé</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="mt-10 flex flex-col-reverse md:flex-row md:items-center justify-between gap-4">
                    <a href="{{ route('aide.nouveaux.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 
                              rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200
                              hover:border-gray-400 hover-lift">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:block text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i> Données sécurisées
                        </div>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r 
                                       from-blue-600 to-blue-700 text-white font-medium rounded-lg 
                                       hover:from-blue-700 hover:to-blue-800 transition-all duration-200
                                       shadow-lg hover:shadow-xl hover-lift">
                            <i class="fas fa-user-plus mr-2"></i> Créer le nouveau
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Pied de page -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p><i class="fas fa-lock mr-1"></i> Toutes les données sont confidentielles et sécurisées</p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Effets sur les inputs
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-300');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-300');
            });
        });
        
        // Validation en temps réel
        const form = document.querySelector('form');
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('border-red-500');
                    this.nextElementSibling?.classList?.remove('hidden');
                } else {
                    this.classList.remove('border-red-500');
                    this.nextElementSibling?.classList?.add('hidden');
                }
            });
        });
        
        // Animation d'entrée
        const formSections = document.querySelectorAll('form > div > div');
        formSections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, index * 150);
        });
        
        // Format téléphone
        const phoneInput = document.getElementById('telephone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) value = value.substring(0, 10);
                
                if (value.length > 6) {
                    value = value.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
                } else if (value.length > 4) {
                    value = value.replace(/(\d{2})(\d{2})(\d{2})/, '$1 $2 $3');
                } else if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d{2})/, '$1 $2');
                }
                
                e.target.value = value;
            });
        }
    });
    </script>
    
</body>
</html>