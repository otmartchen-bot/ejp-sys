@extends('layouts.admin')

@section('title', 'Statistiques')
@section('subtitle', 'Tableau de bord et analyses')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistiques</h1>
            <p class="text-gray-600">Analyses et tendances du système d'intégration</p>
        </div>
        <div class="flex space-x-3">
            <select id="timeframe" 
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
                <option value="quarter">Ce trimestre</option>
                <option value="year">Cette année</option>
            </select>
            <button id="refreshBtn" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Carte Nouveaux -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-user-friends text-blue-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-blue-600">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2" id="totalNouveaux">{{ $totalNouveaux }}</h3>
            <p class="text-gray-600">Nouveaux inscrits</p>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Cette semaine</span>
                    <span class="font-medium text-green-600" id="nouveauxSemaine">+{{ $nouveauxSemaine }}%</span>
                </div>
            </div>
        </div>
        
        <!-- Carte Aides -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-user-shield text-green-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-green-600">Actifs</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2" id="totalAides">{{ $totalAides }}</h3>
            <p class="text-gray-600">Aides à l'intégration</p>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Assignation moyenne</span>
                    <span class="font-medium text-gray-900" id="assignationMoyenne">
                        {{ $assignationMoyenne }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Carte Programmes -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-purple-600">Organisés</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2" id="totalProgrammes">{{ $totalProgrammes }}</h3>
            <p class="text-gray-600">Programmes d'intégration</p>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Cette semaine</span>
                    <span class="font-medium text-gray-900" id="programmesSemaine">{{ $programmesSemaine }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique d'évolution -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Évolution de la participation
                </h3>
                <div class="text-sm text-gray-500" id="chartPeriod">Semaine</div>
            </div>
            <div class="h-64">
                <canvas id="participationChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p>Participation moyenne : <span class="font-medium" id="avgParticipation">{{ $participationMoyenne }}%</span></p>
                <p>Tendance : <span class="font-medium" id="trend">
                    @if($tendanceParticipation > 0)
                        <span class="text-green-600">↑ {{ $tendanceParticipation }}% vs période précédente</span>
                    @elseif($tendanceParticipation < 0)
                        <span class="text-red-600">↓ {{ abs($tendanceParticipation) }}% vs période précédente</span>
                    @else
                        <span class="text-gray-600">→ Stable</span>
                    @endif
                </span></p>
            </div>
        </div>
        
        <!-- Taux de rétention -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Taux de rétention (30 jours)
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Nouveaux Actifs</span>
                        <span class="text-sm font-bold text-green-600" id="retentionActifs">{{ $retentionActifs }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" id="barActifs" style="width: {{ $retentionActifs }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Nouveaux Moyens</span>
                        <span class="text-sm font-bold text-yellow-600" id="retentionMoyens">{{ $retentionMoyens }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-500 h-2.5 rounded-full" id="barMoyens" style="width: {{ $retentionMoyens }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Nouveaux Inactifs</span>
                        <span class="text-sm font-bold text-red-600" id="retentionInactifs">{{ $retentionInactifs }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" id="barInactifs" style="width: {{ $retentionInactifs }}%"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Recommandation</h4>
                        <div class="mt-1 text-sm text-blue-700">
                            <p id="recommandationText">
                                @if($retentionInactifs > 15)
                                    Focus sur les {{ $retentionInactifs }}% inactifs pour améliorer le taux de rétention global.
                                @elseif($retentionMoyens > 30)
                                    Améliorer l'engagement des {{ $retentionMoyens }}% nouveaux moyens pour les convertir en actifs.
                                @else
                                    Taux de rétention satisfaisant. Continuez l'excellent travail !
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des meilleurs aides -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 5 des Aides</h3>
            <p class="mt-1 text-sm text-gray-500">Classement par taux de participation</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aide</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nouveaux</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taux participation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="topAidesBody">
                    @forelse($topAides as $aide)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    @if($aide->photo)
                                        <img src="{{ $aide->photo }}" alt="{{ $aide->prenom }}" class="h-10 w-10 rounded-full">
                                    @else
                                        <i class="fas fa-user text-blue-600"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">{{ $aide->prenom }} {{ $aide->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $aide->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold">{{ $aide->nouveaux_count }}</span> nouveaux
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="h-2 rounded-full 
                                        @if($aide->taux_participation >= 80) bg-green-600
                                        @elseif($aide->taux_participation >= 60) bg-yellow-500
                                        @else bg-red-600 @endif" 
                                        style="width: {{ $aide->taux_participation }}%">
                                    </div>
                                </div>
                                <span class="font-bold 
                                    @if($aide->taux_participation >= 80) text-green-600
                                    @elseif($aide->taux_participation >= 60) text-yellow-600
                                    @else text-red-600 @endif">
                                    {{ $aide->taux_participation }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                if($aide->taux_participation >= 80) {
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Excellent';
                                } elseif($aide->taux_participation >= 60) {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Bon';
                                } else {
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'À améliorer';
                                }
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-user-slash text-3xl mb-3"></i>
                            <p>Aucun aide disponible pour le moment</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.aides.index') }}" 
               class="text-blue-600 hover:text-blue-900 font-medium flex items-center">
                Voir tous les aides <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    
       <!-- Résumé mensuel RÉEL -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            Résumé du mois ({{ now()->translatedFormat('F Y') }})
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                <div class="text-2xl font-bold text-blue-600" id="nouveauxMois">{{ $nouveauxMois }}</div>
                <div class="text-sm text-gray-600">Nouveaux ce mois</div>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition-colors">
                <div class="text-2xl font-bold text-green-600" id="participationMois">{{ $participationMoyenne }}%</div>
                <div class="text-sm text-gray-600">Taux de participation</div>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 transition-colors">
                <div class="text-2xl font-bold text-purple-600" id="programmesMois">{{ $programmesMois }}</div>
                <div class="text-sm text-gray-600">Programmes organisés</div>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-red-50 transition-colors">
                <div class="text-2xl font-bold {{ $absenteismeMois > 20 ? 'text-red-600' : 'text-yellow-600' }}" 
                      id="absenteismeMois">{{ $absenteismeMois }}%</div>
                <div class="text-sm text-gray-600">Taux d'absentéisme</div>
            </div>
        </div>
        
        @if($programmesMois == 0)
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Note :</strong> Aucun programme n'a été organisé ce mois-ci.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let participationChart = null;
    
    // Initialiser le graphique
    function initChart(labels, data, periodLabel) {
        const ctx = document.getElementById('participationChart');
        if (!ctx) return;
        
        // Détruire le graphique existant
        if (participationChart) {
            participationChart.destroy();
        }
        
        // Mettre à jour le label de période
        document.getElementById('chartPeriod').textContent = periodLabel;
        
        // Créer le nouveau graphique
        participationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Participation (%)',
                    data: data,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#1f2937',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `Participation: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            borderDash: [2, 2]
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
    
    // Données initiales (semaine)
    const weekLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    const weekData = [65, 70, 75, 80, 78, 82, 85];
    
    // Initialiser le graphique avec les données de la semaine
    initChart(weekLabels, weekData, 'Semaine');
    
    // Gestion du timeframe
    document.getElementById('timeframe').addEventListener('change', function(e) {
        const period = e.target.value;
        let labels = [];
        let data = [];
        let periodLabel = '';
        
        // Simuler des données différentes selon la période
        switch(period) {
            case 'week':
                labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                data = [65, 70, 75, 80, 78, 82, 85];
                periodLabel = 'Semaine';
                break;
            case 'month':
                labels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
                data = [70, 75, 78, 82];
                periodLabel = 'Mois';
                break;
            case 'quarter':
                labels = ['Mois 1', 'Mois 2', 'Mois 3'];
                data = [72, 76, 80];
                periodLabel = 'Trimestre';
                break;
            case 'year':
                labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                data = [65, 68, 72, 75, 78, 80, 82, 81, 79, 77, 75, 73];
                periodLabel = 'Année';
                break;
        }
        
        // Calculer la moyenne
        const avg = data.reduce((a, b) => a + b, 0) / data.length;
        document.getElementById('avgParticipation').textContent = avg.toFixed(1) + '%';
        
        // Calculer la tendance (simulation)
        const trend = period === 'week' ? 5 : 
                     period === 'month' ? 3 : 
                     period === 'quarter' ? 8 : 12;
        
        const trendElement = document.getElementById('trend');
        if (trend > 0) {
            trendElement.innerHTML = `<span class="text-green-600">↑ ${trend}% vs période précédente</span>`;
        } else if (trend < 0) {
            trendElement.innerHTML = `<span class="text-red-600">↓ ${Math.abs(trend)}% vs période précédente</span>`;
        } else {
            trendElement.innerHTML = `<span class="text-gray-600">→ Stable</span>`;
        }
        
        // Mettre à jour le graphique
        initChart(labels, data, periodLabel);
        
        // Simuler un chargement AJAX (en production, tu ferais une vraie requête)
        simulateDataUpdate(period);
    });
    
    // Fonction pour simuler la mise à jour des données
    function simulateDataUpdate(period) {
        // Afficher un indicateur de chargement
        const refreshBtn = document.getElementById('refreshBtn');
        const originalHTML = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        refreshBtn.disabled = true;
        
        // Simuler un délai de chargement
        setTimeout(() => {
            // Mettre à jour les données selon la période
            const updates = {
                week: {
                    nouveauxSemaine: '+12%',
                    assignationMoyenne: '3.5',
                    programmesSemaine: '3',
                    retentionActifs: 65,
                    retentionMoyens: 25,
                    retentionInactifs: 10
                },
                month: {
                    nouveauxSemaine: '+15%',
                    assignationMoyenne: '3.8',
                    programmesSemaine: '12',
                    retentionActifs: 70,
                    retentionMoyens: 20,
                    retentionInactifs: 10
                },
                quarter: {
                    nouveauxSemaine: '+8%',
                    assignationMoyenne: '3.2',
                    programmesSemaine: '36',
                    retentionActifs: 68,
                    retentionMoyens: 22,
                    retentionInactifs: 10
                },
                year: {
                    nouveauxSemaine: '+25%',
                    assignationMoyenne: '4.1',
                    programmesSemaine: '144',
                    retentionActifs: 72,
                    retentionMoyens: 18,
                    retentionInactifs: 10
                }
            };
            
            const data = updates[period] || updates.week;
            
            // Mettre à jour les éléments
            document.getElementById('nouveauxSemaine').textContent = data.nouveauxSemaine;
            document.getElementById('assignationMoyenne').textContent = data.assignationMoyenne;
            document.getElementById('programmesSemaine').textContent = data.programmesSemaine;
            
            document.getElementById('retentionActifs').textContent = data.retentionActifs + '%';
            document.getElementById('retentionMoyens').textContent = data.retentionMoyens + '%';
            document.getElementById('retentionInactifs').textContent = data.retentionInactifs + '%';
            
            document.getElementById('barActifs').style.width = data.retentionActifs + '%';
            document.getElementById('barMoyens').style.width = data.retentionMoyens + '%';
            document.getElementById('barInactifs').style.width = data.retentionInactifs + '%';
            
            // Mettre à jour la recommandation
            const recommandationText = document.getElementById('recommandationText');
            if (data.retentionInactifs > 15) {
                recommandationText.textContent = `Focus sur les ${data.retentionInactifs}% inactifs pour améliorer le taux de rétention global.`;
            } else if (data.retentionMoyens > 25) {
                recommandationText.textContent = `Améliorer l'engagement des ${data.retentionMoyens}% nouveaux moyens pour les convertir en actifs.`;
            } else {
                recommandationText.textContent = 'Taux de rétention satisfaisant. Continuez l\'excellent travail !';
            }
            
            // Mettre à jour le résumé mensuel
            const nouveauxMois = period === 'month' ? 25 : 
                               period === 'quarter' ? 75 : 
                               period === 'year' ? 300 : 12;
            
            document.getElementById('nouveauxMois').textContent = nouveauxMois;
            document.getElementById('participationMois').textContent = data.retentionActifs;
            document.getElementById('programmesMois').textContent = data.programmesSemaine * 4;
            document.getElementById('absenteismeMois').textContent = data.retentionInactifs;
            
            // Restaurer le bouton
            refreshBtn.innerHTML = originalHTML;
            refreshBtn.disabled = false;
            
            // Afficher un message de succès
            showNotification('Données mises à jour pour la période sélectionnée', 'success');
            
        }, 1500);
    }
    
    // Gestion du bouton rafraîchir
    document.getElementById('refreshBtn').addEventListener('click', function() {
        const period = document.getElementById('timeframe').value;
        simulateDataUpdate(period);
    });
    
    // Fonction pour afficher les notifications
    function showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-0 opacity-100 ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Ajouter au document
        document.body.appendChild(notification);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Ajouter des effets hover supplémentaires
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animer les barres de progression
    setTimeout(() => {
        const bars = document.querySelectorAll('.rounded-full.bg-green-600, .rounded-full.bg-yellow-500, .rounded-full.bg-red-600');
        bars.forEach(bar => {
            const originalWidth = bar.style.width;
            bar.style.width = '0%';
            
            setTimeout(() => {
                bar.style.transition = 'width 1.5s ease-out';
                bar.style.width = originalWidth;
            }, 100);
        });
    }, 500);
});
</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showNotification('{{ session('success') }}', 'success');
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showNotification('{{ session('error') }}', 'error');
});
</script>
@endif

<style>
/* Animations supplémentaires */
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

.bg-white {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

.bg-white:nth-child(1) { animation-delay: 0.1s; }
.bg-white:nth-child(2) { animation-delay: 0.2s; }
.bg-white:nth-child(3) { animation-delay: 0.3s; }
.bg-white:nth-child(4) { animation-delay: 0.4s; }
.bg-white:nth-child(5) { animation-delay: 0.5s; }

/* Effets glassmorphism pour le sélecteur */
#timeframe {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.9);
}

/* Effets de transition pour les boutons */
button, a {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de survol pour les lignes du tableau */
tbody tr {
    transition: background-color 0.2s ease;
}

tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.05);
}
</style>
@endsection