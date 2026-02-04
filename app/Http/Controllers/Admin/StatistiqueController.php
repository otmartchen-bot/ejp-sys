<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Programme;
use App\Models\Participation;
use App\Models\Nouveau;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Récupérer la période depuis la requête (par défaut: semaine)
        $period = request()->get('period', 'week');
        
        // Données principales RÉELLES
        $totalNouveaux = Nouveau::count();
        $totalAides = User::where('role', 'aide')->count();
        $totalProgrammes = Programme::count();
        
        // Données calculées RÉELLES
        $assignationMoyenne = $this->calculateAssignationMoyenne($totalNouveaux, $totalAides);
        $nouveauxSemaine = $this->calculateNouveauxSemaine();
        $programmesSemaine = $this->getProgrammesThisWeek();
        
        // Données RÉELLES pour tous les champs
        $retentionData = $this->getRealRetentionData();
        $topAides = $this->getRealTopAides(5);
        $summaryData = $this->getRealMonthlySummary(); // C'EST ICI LE PROBLÈME !
        
        // Graphiques avec données RÉELLES
        $chartData = $this->getRealChartData($period);
        
        return view('admin.statistiques.index', [
            // Données globales
            'totalNouveaux' => $totalNouveaux,
            'totalAides' => $totalAides,
            'totalProgrammes' => $totalProgrammes,
            
            // Données calculées
            'assignationMoyenne' => $assignationMoyenne,
            'nouveauxSemaine' => $nouveauxSemaine,
            'programmesSemaine' => $programmesSemaine,
            
            // Données de rétention
            'retentionActifs' => $retentionData['actifs'],
            'retentionMoyens' => $retentionData['moyens'],
            'retentionInactifs' => $retentionData['inactifs'],
            
            // Données des aides
            'topAides' => $topAides,
            
            // Résumé mensuel RÉEL
            'nouveauxMois' => $summaryData['nouveaux'],
            'programmesMois' => $summaryData['programmes'],
            'participationMoyenne' => $summaryData['participation'],
            'tendanceParticipation' => $summaryData['tendance'],
            'absenteismeMois' => $summaryData['absenteisme'],
            'participationMois' => $summaryData['participation'],
            
            // Données pour les graphiques
            'chartLabels' => $chartData['labels'],
            'chartData' => $chartData['data'],
            'chartAverage' => $chartData['average'],
            'chartTrend' => $chartData['trend'],
            'currentPeriod' => $period,
        ]);
    }
    
    /**
     * Méthodes RÉELLES pour les calculs
     */
    
    private function calculateAssignationMoyenne($totalNouveaux, $totalAides)
    {
        if ($totalAides > 0) {
            return round($totalNouveaux / $totalAides, 1);
        }
        return 0;
    }
    
    private function calculateNouveauxSemaine()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $nouveauxThisWeek = Nouveau::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek = Carbon::now()->subWeek()->endOfWeek();
        
        $nouveauxLastWeek = Nouveau::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        
        // Calculer le pourcentage de changement
        if ($nouveauxLastWeek > 0) {
            $change = (($nouveauxThisWeek - $nouveauxLastWeek) / $nouveauxLastWeek) * 100;
            return round($change);
        }
        
        return $nouveauxThisWeek > 0 ? 100 : 0;
    }
    
    private function getProgrammesThisWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        return Programme::whereBetween('date_programme', [$startOfWeek, $endOfWeek])->count();
    }
    
    private function getRealRetentionData()
    {
        $totalNouveaux = Nouveau::count();
        
        if ($totalNouveaux === 0) {
            return [
                'actifs' => 0,
                'moyens' => 0,
                'inactifs' => 0,
            ];
        }
        
        // Début de la période d'analyse (30 jours)
        $startDate = Carbon::now()->subDays(30);
        
        // Actifs : au moins 3 présences dans les 30 jours
        $actifs = Nouveau::whereHas('participations', function($query) use ($startDate) {
                $query->where('present', true)
                      ->where('enregistre_le', '>=', $startDate);
            }, '>=', 3)
            ->count();
        
        // Moyens : 1-2 présences dans les 30 jours
        $moyens = Nouveau::whereHas('participations', function($query) use ($startDate) {
                $query->where('present', true)
                      ->where('enregistre_le', '>=', $startDate);
            }, '>=', 1)
            ->count() - $actifs;
        
        // Inactifs : aucune présence dans les 30 jours
        $inactifs = $totalNouveaux - $actifs - $moyens;
        
        // Calculer les pourcentages
        return [
            'actifs' => $totalNouveaux > 0 ? round(($actifs / $totalNouveaux) * 100) : 0,
            'moyens' => $totalNouveaux > 0 ? round(($moyens / $totalNouveaux) * 100) : 0,
            'inactifs' => $totalNouveaux > 0 ? round(($inactifs / $totalNouveaux) * 100) : 0,
        ];
    }
    
    private function getRealTopAides($limit = 5)
    {
        $aides = User::where('role', 'aide')
            ->withCount('nouveaux')
            ->get()
            ->map(function($aide) {
                $aide->taux_participation = 0;
                
                if ($aide->nouveaux_count > 0) {
                    // Récupérer les IDs des nouveaux de cet aide
                    $nouveauxIds = Nouveau::where('aide_id', $aide->id)->pluck('id');
                    
                    // Calculer les présences des nouveaux de cet aide ce mois-ci
                    $presences = Participation::whereIn('nouveau_id', $nouveauxIds)
                        ->where('present', true)
                        ->whereMonth('enregistre_le', now()->month)
                        ->count();
                    
                    // Nombre de programmes ce mois-ci
                    $programmesThisMonth = Programme::whereMonth('date_programme', now()->month)->count();
                    
                    if ($programmesThisMonth > 0) {
                        $maxPossible = $aide->nouveaux_count * $programmesThisMonth;
                        $aide->taux_participation = $maxPossible > 0 
                            ? round(($presences / $maxPossible) * 100) 
                            : 0;
                    }
                }
                
                // Ajouter des informations pour l'affichage
                $aide->nom_complet = $aide->name ?: $aide->email;
                $aide->email_display = $aide->email;
                
                return $aide;
            })
            ->sortByDesc('taux_participation')
            ->take($limit);
        
        return $aides;
    }
    
    private function getRealMonthlySummary()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // NOUVEAUX CE MOIS (VRAI)
        $nouveauxMois = Nouveau::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        
        // PROGRAMMES CE MOIS (VRAI)
        $programmesMois = Programme::whereBetween('date_programme', [$startOfMonth, $endOfMonth])
            ->count();
        
        // TAUX DE PARTICIPATION MOYEN CE MOIS (VRAI)
        $presencesMois = Participation::whereBetween('enregistre_le', [$startOfMonth, $endOfMonth])
            ->where('present', true)
            ->count();
        
        $totalNouveaux = Nouveau::count();
        $participationMoyenne = 0;
        
        if ($programmesMois > 0 && $totalNouveaux > 0) {
            $participationMoyenne = round(($presencesMois / ($totalNouveaux * $programmesMois)) * 100);
        }
        
        // TAUX D'ABSENTÉISME CE MOIS (VRAI)
        $totalPresences = Participation::whereBetween('enregistre_le', [$startOfMonth, $endOfMonth])
            ->where('present', true)
            ->count();
            
        $totalAbsences = Participation::whereBetween('enregistre_le', [$startOfMonth, $endOfMonth])
            ->where('present', false)
            ->count();
            
        $total = $totalPresences + $totalAbsences;
        $absenteisme = $total > 0 ? round(($totalAbsences / $total) * 100) : 0;
        
        // TENDANCE (simplifiée pour l'instant)
        $tendance = 0;
        
        return [
            'nouveaux' => $nouveauxMois, // VRAI
            'programmes' => $programmesMois, // VRAI
            'participation' => $participationMoyenne, // VRAI
            'tendance' => $tendance, // Peut être calculé plus précisément
            'absenteisme' => $absenteisme, // VRAI
        ];
    }
    
    private function getRealChartData($period)
    {
        $labels = [];
        $data = [];
        $total = 0;
        
        switch ($period) {
            case 'week':
                // 7 derniers jours
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->translatedFormat('D');
                    
                    // Présences pour ce jour
                    $presences = Participation::whereDate('enregistre_le', $date)
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereDate('date_programme', $date)->count();
                    $nouveaux = Nouveau::count();
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveaux > 0) {
                        $taux = round(($presences / ($nouveaux * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'month':
                // 4 dernières semaines
                for ($i = 3; $i >= 0; $i--) {
                    $weekStart = Carbon::now()->subWeeks($i + 1);
                    $weekEnd = Carbon::now()->subWeeks($i);
                    $labels[] = 'Sem ' . (4 - $i);
                    
                    $presences = Participation::whereBetween('enregistre_le', [$weekStart, $weekEnd])
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$weekStart, $weekEnd])->count();
                    $nouveaux = Nouveau::count();
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveaux > 0) {
                        $taux = round(($presences / ($nouveaux * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'quarter':
                // 3 derniers mois
                for ($i = 2; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $labels[] = $month->translatedFormat('M');
                    
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    
                    $presences = Participation::whereBetween('enregistre_le', [$monthStart, $monthEnd])
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$monthStart, $monthEnd])->count();
                    $nouveaux = Nouveau::count();
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveaux > 0) {
                        $taux = round(($presences / ($nouveaux * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'year':
                // 12 derniers mois
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $labels[] = $month->translatedFormat('M');
                    
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    
                    $presences = Participation::whereBetween('enregistre_le', [$monthStart, $monthEnd])
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$monthStart, $monthEnd])->count();
                    $nouveaux = Nouveau::count();
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveaux > 0) {
                        $taux = round(($presences / ($nouveaux * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
        }
        
        $average = count($data) > 0 ? round($total / count($data), 1) : 0;
        
        return [
            'labels' => $labels,
            'data' => $data,
            'average' => $average,
            'trend' => 0, // Pour simplifier pour l'instant
        ];
    }
    
    /**
     * API pour AJAX
     */
    public function getData(Request $request)
    {
        $period = $request->get('period', 'week');
        
        $data = [
            'success' => true,
            'period' => $period,
            'stats' => [
                'totalNouveaux' => Nouveau::count(),
                'totalAides' => User::where('role', 'aide')->count(),
                'totalProgrammes' => Programme::count(),
            ],
            'nouveauxSemaine' => $this->calculateNouveauxSemaine(),
            'programmesSemaine' => $this->getProgrammesThisWeek(),
            'retentionData' => $this->getRealRetentionData(),
            'topAides' => $this->getRealTopAides(5),
            'summaryData' => $this->getRealMonthlySummary(),
            'chartData' => $this->getRealChartData($period),
        ];
        
        return response()->json($data);
    }
}