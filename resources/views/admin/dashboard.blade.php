@extends('layouts.admin')

@section('title', 'Tableau de Bord Admin')
@section('subtitle', 'Vue d\'ensemble du système')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Total Aides</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_aides'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Total Nouveaux</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_nouveaux'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Total Programmes</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_programmes'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Nouveaux ce mois</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['nouveaux_ce_mois'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <h3 class="text-xl font-bold mb-4">Évolution cette semaine</h3>
            <div class="h-64">
                @if(!empty($evolutionData) && count($evolutionData) > 0)
                    <canvas id="evolutionChart"></canvas>
                @else
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-4xl mb-2"></i>
                            <p>Aucune donnée disponible</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="glass-card bg-white p-6 rounded-xl shadow transition-all duration-300 hover:shadow-lg">
            <h3 class="text-xl font-bold mb-4">Taux de rétention</h3>
            <div class="h-64 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-5xl font-bold text-green-600">{{ $retentionRate ?? 0 }}%</div>
                    <p class="text-gray-600 mt-2">Nouveaux actifs (30 jours)</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Boutons d'action FONCTIONNELS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Créer un Aide -->
        <div class="glass-card bg-white p-6 rounded-xl shadow border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200">
            <a href="{{ route('admin.aides.create') }}" class="block">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-lg transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 group-hover:text-blue-700">Créer un Aide</h4>
                        <p class="text-gray-600 text-sm mt-1">Ajouter un nouvel aide</p>
                        <div class="mt-3 flex items-center text-blue-600 font-medium group-hover:text-blue-800">
                            <i class="fas fa-plus mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
                            <span>Cliquez pour créer</span>
                            <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 group-hover:ml-3 transition-all duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Créer Programme -->
        <div class="glass-card bg-white p-6 rounded-xl shadow border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-green-200">
            <a href="{{ route('admin.programmes.create') }}" class="block">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-green-100 to-green-200 p-4 rounded-lg transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-calendar-plus text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 group-hover:text-green-700">Créer Programme</h4>
                        <p class="text-gray-600 text-sm mt-1">Planifier programme</p>
                        <div class="mt-3 flex items-center text-green-600 font-medium group-hover:text-green-800">
                            <i class="fas fa-plus mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
                            <span>Cliquez pour créer</span>
                            <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 group-hover:ml-3 transition-all duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Ajouter Nouveau -->
        <div class="glass-card bg-white p-6 rounded-xl shadow border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-purple-200">
            <a href="{{ route('admin.nouveaux.create') }}" class="block">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-4 rounded-lg transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-user-friends text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 group-hover:text-purple-700">Ajouter Nouveau</h4>
                        <p class="text-gray-600 text-sm mt-1">Enregistrer participant</p>
                        <div class="mt-3 flex items-center text-purple-600 font-medium group-hover:text-purple-800">
                            <i class="fas fa-plus mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
                            <span>Cliquez pour créer</span>
                            <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 group-hover:ml-3 transition-all duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@if(!empty($evolutionData) && count($evolutionData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart');
    if (ctx) {
        // Convertir les données PHP en JavaScript
        const labels = @json(array_keys($evolutionData ?? []));
        const data = @json(array_values($evolutionData ?? []));
        
        // Si pas de données réelles, utiliser des données de démonstration
        const chartLabels = labels.length > 0 ? labels : ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        const chartData = data.length > 0 ? data : [0, 0, 0, 0, 0, 0, 0];
        
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Participations',
                    data: chartData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear'
                    }
                }
            }
        });
    }
});
</script>
@endif

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.glass-card:hover {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.group-hover\:scale-110:hover {
    transform: scale(1.1);
}

.group-hover\:rotate-90:hover {
    transform: rotate(90deg);
}

a {
    text-decoration: none !important;
    color: inherit;
}

.group {
    position: relative;
}

.group:hover .group-hover\:block {
    display: block;
}

/* Animation pour les cartes de statistiques */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.glass-card {
    animation: fadeInUp 0.6s ease-out;
}

.glass-card:nth-child(1) { animation-delay: 0.1s; }
.glass-card:nth-child(2) { animation-delay: 0.2s; }
.glass-card:nth-child(3) { animation-delay: 0.3s; }
.glass-card:nth-child(4) { animation-delay: 0.4s; }

/* Effet de pulse pour les statistiques importantes */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.text-3xl.font-bold {
    transition: color 0.3s ease;
}

.glass-card:hover .text-3xl.font-bold {
    color: #2563eb; /* blue-600 */
    animation: pulse 2s infinite;
}
</style>
@endsection