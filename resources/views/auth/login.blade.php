<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EJP Système</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Drapeau ivoirien en arrière-plan */
        .flag-background {
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #f77f00 0%,    /* Orange vif */
                #f77f00 30.66%,
                #ffffff 20.33%, /* Blanc */
                #ffffff 40.66%,
                #202120 66.66%, /* Vert */
                #202120 100%
            );
            background-size: 400% 400%;
            animation: flagWave 15s ease infinite;
            position: relative;
        }
        
        /* Animation douce du drapeau */
        @keyframes flagWave {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
        
        /* Overlay léger pour lisibilité */
        .flag-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
        }
        
        /* Effet glassmorphism amélioré */
        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.1),
                0 8px 15px rgba(0, 0, 0, 0.07),
                inset 0 0 0 1px rgba(255, 255, 255, 0.3);
        }
        
        /* Container pour le logo avec proportions spécifiques */
        .logo-container {
            position: relative;
            margin: 0 auto;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.15),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        /* Animation au survol */
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .hover-lift:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 15px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Bouton avec gradient drapeau */
        .btn-flag {
            background: linear-gradient(
                90deg,
                #f77f00 0%,    /* Orange */
                #ffffff 50%,    /* Blanc */
                #202120 100%    /* Vert */
            );
            background-size: 200% 100%;
            color: #333;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            transition: all 0.4s ease;
        }
        
        .btn-flag:hover {
            background-position: 100% 0;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Input style avec effet glass */
        .input-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }
        
        .input-glass:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #f77f00;
            box-shadow: 
                0 0 0 3px rgba(247, 127, 0, 0.15),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }
        
        /* Checkbox personnalisée */
        .checkbox-custom {
            accent-color: #f77f00;
            width: 18px;
            height: 18px;
            border-radius: 4px;
        }
        
        /* Texte avec effet de transparence */
        .text-glow {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Container principal */
        .main-container {
            position: relative;
            z-index: 10;
            padding-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .logo-container {
                width: 140px;
                height: 140px;
            }
            
            .glass-card {
                border-radius: 20px;
                margin: 1rem;
            }
        }
    </style>
</head>
<body class="flag-background">
    <div class="main-container min-h-screen flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
        <!-- Logo dans un container spécifique -->
        <div class="logo-container hover-lift mx-auto">
            <!-- Remplace par ton logo -->
            <img src="/images/logo.png" 
                 alt="Logo EJP" 
                 class="w-32 h-32 object-contain"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-3xl font-bold text-gray-800\'>EJP</span>'">
        </div>

        <!-- Carte de connexion -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="glass-card hover-lift py-10 px-8 sm:rounded-2xl sm:px-10">
                <!-- Titre avec effet -->
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 text-glow mb-2">
                        EJP Système
                    </h1>
                    <p class="text-gray-700 font-medium">
                        Système d'Intégration des Nouveaux
                    </p>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-envelope mr-2 text-orange-600"></i>
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   class="input-glass pl-10 w-full px-4 py-3 rounded-xl focus:outline-none"
                                   placeholder="votre@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-8">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-lock mr-2 text-green-600"></i>
                            Mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required
                                   class="input-glass pl-10 w-full px-4 py-3 rounded-xl focus:outline-none"
                                   placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" 
                                        onclick="togglePassword()"
                                        class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox"
                                   class="checkbox-custom"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="ml-3 block text-sm text-gray-700 font-medium">
                                Rester connecté
                            </label>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="mb-6">
                        <button type="submit"
                                class="btn-flag w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-white font-semibold tracking-wide">
                            <i class="fas fa-sign-in-alt mr-3"></i>
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- Lien pour mot de passe oublié -->
                <div class="text-center pt-6 border-t border-gray-200">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm font-medium text-gray-600 hover:text-orange-600 transition-colors duration-300">
                            <i class="fas fa-question-circle mr-2"></i>
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-10 text-center">
                <p class="text-sm font-medium text-white text-glow">
                    &copy; {{ date('Y') }} Église des Jeunes 
                </p>
                <p class="text-xs text-gray-200 mt-1">
                    Version 1.0 • Protection des données
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('#password + .absolute button i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }
        
        // Effet de focus sur les inputs
        document.querySelectorAll('.input-glass').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-1px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
        
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.glass-card');
            const logo = document.querySelector('.logo-container');
            
            // Animation d'entrée
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease-out';
                
                logo.style.opacity = '0';
                logo.style.transform = 'scale(0.8)';
                logo.style.transition = 'all 0.6s ease-out';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                    
                    logo.style.opacity = '1';
                    logo.style.transform = 'scale(1)';
                }, 100);
            }, 50);
        });
    </script>
</body>
</html>