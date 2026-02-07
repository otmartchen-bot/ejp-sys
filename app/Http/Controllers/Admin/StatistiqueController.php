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
        $dateRef = request()->get('date_ref', now()->format('Y-m-d'));
        
        // Données principales RÉELLES
        $totalNouveaux = Nouveau::count();
        $totalAides = User::where('role', 'aide')->count();
        $totalProgrammes = Programme::count();
        
        // Données calculées RÉELLES (adaptées à la période)
        $assignationMoyenne = $this->calculateAssignationMoyenne($totalNouveaux, $totalAides);
        $nouveauxSemaine = $this->calculateNouveauxParPeriode($period, $dateRef);
        $programmesSemaine = $this->getProgrammesParPeriode($period, $dateRef);
        
        // Données RÉELLES pour tous les champs (adaptées à la période)
        $retentionData = $this->getRealRetentionData($period, $dateRef);
        $topAides = $this->getRealTopAides(5, $period, $dateRef);
        $summaryData = $this->getSummaryParPeriode($period, $dateRef);
        
        // Graphiques avec données RÉELLES
        $chartData = $this->getRealChartData($period, $dateRef);
        
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
            
            // Résumé DYNAMIQUE selon la période
            'nouveauxMois' => $summaryData['nouveaux'],
            'programmesMois' => $summaryData['programmes'],
            'participationMoyenne' => $summaryData['participation'],
            'tendanceParticipation' => $summaryData['tendance'],
            'absenteismeMois' => $summaryData['absenteisme'],
            
            // Données pour les graphiques
            'chartLabels' => $chartData['labels'],
            'chartData' => $chartData['data'],
            'chartAverage' => $chartData['average'],
            'chartTrend' => $chartData['trend'],
            'currentPeriod' => $period,
            'currentDateRef' => $dateRef,
        ]);
    }
    
    private function getDateRange($period, $dateRef = null)
    {
        $referenceDate = $dateRef ? Carbon::parse($dateRef) : Carbon::now();
        
        switch ($period) {
            case 'week':
                return [
                    'start' => $referenceDate->copy()->startOfWeek(),
                    'end' => $referenceDate->copy()->endOfWeek(),
                    'previous_start' => $referenceDate->copy()->subWeek()->startOfWeek(),
                    'previous_end' => $referenceDate->copy()->subWeek()->endOfWeek(),
                ];
                
            case 'month':
                return [
                    'start' => $referenceDate->copy()->startOfMonth(),
                    'end' => $referenceDate->copy()->endOfMonth(),
                    'previous_start' => $referenceDate->copy()->subMonth()->startOfMonth(),
                    'previous_end' => $referenceDate->copy()->subMonth()->endOfMonth(),
                ];
                
            case 'quarter':
                return [
                    'start' => $referenceDate->copy()->startOfQuarter(),
                    'end' => $referenceDate->copy()->endOfQuarter(),
                    'previous_start' => $referenceDate->copy()->subQuarter()->startOfQuarter(),
                    'previous_end' => $referenceDate->copy()->subQuarter()->endOfQuarter(),
                ];
                
            case 'year':
                return [
                    'start' => $referenceDate->copy()->startOfYear(),
                    'end' => $referenceDate->copy()->endOfYear(),
                    'previous_start' => $referenceDate->copy()->subYear()->startOfYear(),
                    'previous_end' => $referenceDate->copy()->subYear()->endOfYear(),
                ];
                
            default:
                return [
                    'start' => $referenceDate->copy()->startOfWeek(),
                    'end' => $referenceDate->copy()->endOfWeek(),
                    'previous_start' => $referenceDate->copy()->subWeek()->startOfWeek(),
                    'previous_end' => $referenceDate->copy()->subWeek()->endOfWeek(),
                ];
        }
    }
    
    private function getSummaryParPeriode($period, $dateRef = null)
    {
        $dates = $this->getDateRange($period, $dateRef);
        
        // NOUVEAUX dans la période
        $nouveauxPeriode = Nouveau::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->count();
        
        // PROGRAMMES dans la période
        $programmesPeriode = Programme::whereBetween('date_programme', [$dates['start'], $dates['end']])
            ->count();
        
        // CALCUL PRÉCIS de la participation
        $presencesPeriode = Participation::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('present', true)
            ->count();
        
        // Nombre de nouveaux qui ont participé au moins une fois dans la période
        $nouveauxAvecParticipation = Participation::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('present', true)
            ->distinct('nouveau_id')
            ->count('nouveau_id');
        
        $participationMoyenne = 0;
        
        if ($programmesPeriode > 0 && $nouveauxAvecParticipation > 0) {
            // Calcul : (présences totales / (nouveaux participants * programmes)) * 100
            $maxPossible = $nouveauxAvecParticipation * $programmesPeriode;
            $participationMoyenne = round(($presencesPeriode / $maxPossible) * 100);
        }
        
        // TAUX D'ABSENTÉISME dans la période
        $totalPresences = Participation::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('present', true)
            ->count();
            
        $totalAbsences = Participation::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('present', false)
            ->count();
            
        $total = $totalPresences + $totalAbsences;
        $absenteisme = $total > 0 ? round(($totalAbsences / $total) * 100) : 0;
        
        // TENDANCE : Comparaison avec la période précédente
        $presencesPrev = Participation::whereBetween('created_at', [$dates['previous_start'], $dates['previous_end']])
            ->where('present', true)
            ->count();
            
        $programmesPrev = Programme::whereBetween('date_programme', [$dates['previous_start'], $dates['previous_end']])
            ->count();
            
        $nouveauxAvecParticipationPrev = Participation::whereBetween('created_at', [$dates['previous_start'], $dates['previous_end']])
            ->where('present', true)
            ->distinct('nouveau_id')
            ->count('nouveau_id');
        
        $tendance = 0;
        if ($presencesPrev > 0 && $programmesPrev > 0 && $nouveauxAvecParticipationPrev > 0) {
            $maxPossiblePrev = $nouveauxAvecParticipationPrev * $programmesPrev;
            $participationPrev = round(($presencesPrev / $maxPossiblePrev) * 100);
            
            if ($participationPrev > 0 && $participationMoyenne > 0) {
                $tendance = round((($participationMoyenne - $participationPrev) / $participationPrev) * 100, 1);
            }
        }
        
        return [
            'nouveaux' => $nouveauxPeriode,
            'programmes' => $programmesPeriode,
            'participation' => $participationMoyenne,
            'tendance' => $tendance,
            'absenteisme' => $absenteisme,
        ];
    }
    
    private function calculateNouveauxParPeriode($period, $dateRef = null)
    {
        $dates = $this->getDateRange($period, $dateRef);
        
        $nouveauxThisPeriod = Nouveau::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        $nouveauxPrevPeriod = Nouveau::whereBetween('created_at', [$dates['previous_start'], $dates['previous_end']])->count();
        
        // Calculer le pourcentage de changement
        if ($nouveauxPrevPeriod > 0) {
            $change = (($nouveauxThisPeriod - $nouveauxPrevPeriod) / $nouveauxPrevPeriod) * 100;
            return round($change);
        }
        
        return $nouveauxThisPeriod > 0 ? 100 : 0;
    }
    
    private function getProgrammesParPeriode($period, $dateRef = null)
    {
        $dates = $this->getDateRange($period, $dateRef);
        
        return Programme::whereBetween('date_programme', [$dates['start'], $dates['end']])->count();
    }
    
    private function getRealRetentionData($period = null, $dateRef = null)
    {
        $dates = $this->getDateRange($period ?? 'month', $dateRef);
        
        // Nombre de nouveaux dans la période d'analyse (30 jours avant la fin de la période)
        $startAnalysis = $dates['end']->copy()->subDays(30);
        
        $nouveauxInPeriod = Nouveau::where('created_at', '<=', $dates['end'])
            ->where('created_at', '>=', $dates['start']->copy()->subMonths(2))
            ->count();
        
        if ($nouveauxInPeriod === 0) {
            return [
                'actifs' => 0,
                'moyens' => 0,
                'inactifs' => 0,
            ];
        }
        
        // Actifs : au moins 3 présences dans les 30 derniers jours de la période
        $actifs = Nouveau::where('created_at', '<=', $dates['end'])
            ->whereHas('participations', function($query) use ($startAnalysis, $dates) {
                $query->where('present', true)
                      ->where('created_at', '>=', $startAnalysis); // CORRIGÉ ICI
            }, '>=', 3)
            ->count();
        
        // Moyens : 1-2 présences
        $moyens = Nouveau::where('created_at', '<=', $dates['end'])
            ->whereHas('participations', function($query) use ($startAnalysis, $dates) {
                $query->where('present', true)
                      ->where('created_at', '>=', $startAnalysis); // CORRIGÉ ICI
            }, '>=', 1)
            ->count() - $actifs;
        
        // Inactifs : aucune présence
        $inactifs = $nouveauxInPeriod - $actifs - $moyens;
        
        // Calculer les pourcentages
        return [
            'actifs' => $nouveauxInPeriod > 0 ? round(($actifs / $nouveauxInPeriod) * 100) : 0,
            'moyens' => $nouveauxInPeriod > 0 ? round(($moyens / $nouveauxInPeriod) * 100) : 0,
            'inactifs' => $nouveauxInPeriod > 0 ? round(($inactifs / $nouveauxInPeriod) * 100) : 0,
        ];
    }
    
    private function getRealTopAides($limit = 5, $period = null, $dateRef = null)
    {
        $dates = $this->getDateRange($period ?? 'month', $dateRef);
        
        $aides = User::where('role', 'aide')
            ->withCount(['nouveaux' => function($query) use ($dates) {
                $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
            }])
            ->with(['nouveaux' => function($query) use ($dates) {
                $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
            }])
            ->get()
            ->map(function($aide) use ($dates) {
                // Récupérer les IDs des nouveaux de cet aide dans la période
                $nouveauxIds = $aide->nouveaux->pluck('id');
                
                // Nombre de nouveaux dans la période
                $aide->nouveaux_periode_count = $aide->nouveaux_count;
                
                // Initialiser le taux à 0
                $aide->taux_participation = 0;
                
                if ($nouveauxIds->count() > 0) {
                    // Calculer les présences des nouveaux de cet aide dans la période
                    $presences = Participation::whereIn('nouveau_id', $nouveauxIds)
                        ->where('present', true)
                        ->whereBetween('created_at', [$dates['start'], $dates['end']]) // CORRIGÉ ICI
                        ->count();
                    
                    // Nombre de programmes dans la période
                    $programmesPeriode = Programme::whereBetween('date_programme', [$dates['start'], $dates['end']])
                        ->count();
                    
                    // Nombre de nouveaux UNIQUES qui ont participé
                    $nouveauxAvecParticipation = Participation::whereIn('nouveau_id', $nouveauxIds)
                        ->whereBetween('created_at', [$dates['start'], $dates['end']]) // CORRIGÉ ICI
                        ->distinct('nouveau_id')
                        ->count('nouveau_id');
                    
                    if ($programmesPeriode > 0 && $nouveauxAvecParticipation > 0) {
                        // Calcul plus précis : (présences totales / (nouveaux * programmes)) * 100
                        $maxPossible = $nouveauxAvecParticipation * $programmesPeriode;
                        $aide->taux_participation = $maxPossible > 0 
                            ? round(($presences / $maxPossible) * 100) 
                            : 0;
                    }
                }
                
                // Ajouter des informations pour l'affichage
                $aide->nom_complet = $aide->nomComplet ?? $aide->name ?? $aide->email;
                $aide->email_display = $aide->email;
                
                // Calculer le statut
                if ($aide->taux_participation >= 70) {
                    $aide->statut_label = 'Excellent';
                } elseif ($aide->taux_participation >= 40) {
                    $aide->statut_label = 'Bon';
                } else {
                    $aide->statut_label = 'Améliorer';
                }
                
                return $aide;
            })
            ->sortByDesc('taux_participation')
            ->take($limit);
        
        return $aides;
    }
    
    private function getRealChartData($period, $dateRef = null)
    {
        $dates = $this->getDateRange($period, $dateRef);
        
        $labels = [];
        $data = [];
        $total = 0;
        
        switch ($period) {
            case 'week':
                // 7 jours de la semaine sélectionnée
                for ($i = 0; $i < 7; $i++) {
                    $date = $dates['start']->copy()->addDays($i);
                    $labels[] = $date->translatedFormat('D d/m');
                    
                    // Présences pour ce jour
                    $presences = Participation::whereDate('created_at', $date) // CORRIGÉ ICI
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereDate('date_programme', $date)->count();
                    
                    // Nouveaux qui ont participé ce jour
                    $nouveauxParticipants = Participation::whereDate('created_at', $date) // CORRIGÉ ICI
                        ->where('present', true)
                        ->distinct('nouveau_id')
                        ->count('nouveau_id');
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveauxParticipants > 0) {
                        $taux = round(($presences / ($nouveauxParticipants * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'month':
                // 4-5 semaines du mois
                $start = $dates['start'];
                $end = $dates['end'];
                $weekCount = ceil($start->diffInDays($end) / 7);
                
                for ($week = 1; $week <= $weekCount; $week++) {
                    $weekStart = $start->copy()->addWeeks($week - 1);
                    $weekEnd = ($week === $weekCount) ? $end : $weekStart->copy()->addDays(6)->endOfDay();
                    
                    $labels[] = 'Sem ' . $week;
                    
                    $presences = Participation::whereBetween('created_at', [$weekStart, $weekEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$weekStart, $weekEnd])->count();
                    
                    // Nouveaux qui ont participé cette semaine
                    $nouveauxParticipants = Participation::whereBetween('created_at', [$weekStart, $weekEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->distinct('nouveau_id')
                        ->count('nouveau_id');
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveauxParticipants > 0) {
                        $taux = round(($presences / ($nouveauxParticipants * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'quarter':
                // 3 mois du trimestre
                for ($i = 0; $i < 3; $i++) {
                    $month = $dates['start']->copy()->addMonths($i);
                    $labels[] = $month->translatedFormat('M Y');
                    
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    
                    // Ajuster pour le trimestre
                    if ($i == 0 && $monthStart < $dates['start']) {
                        $monthStart = $dates['start'];
                    }
                    if ($i == 2 && $monthEnd > $dates['end']) {
                        $monthEnd = $dates['end'];
                    }
                    
                    $presences = Participation::whereBetween('created_at', [$monthStart, $monthEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$monthStart, $monthEnd])->count();
                    
                    // Nouveaux qui ont participé ce mois
                    $nouveauxParticipants = Participation::whereBetween('created_at', [$monthStart, $monthEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->distinct('nouveau_id')
                        ->count('nouveau_id');
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveauxParticipants > 0) {
                        $taux = round(($presences / ($nouveauxParticipants * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
                
            case 'year':
                // 12 mois de l'année
                for ($i = 0; $i < 12; $i++) {
                    $month = $dates['start']->copy()->addMonths($i);
                    
                    // Arrêter si on dépasse la fin de l'année
                    if ($month > $dates['end']) break;
                    
                    $labels[] = $month->translatedFormat('M');
                    
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();
                    
                    // Ajuster pour l'année
                    if ($i == 0 && $monthStart < $dates['start']) {
                        $monthStart = $dates['start'];
                    }
                    if ($monthEnd > $dates['end']) {
                        $monthEnd = $dates['end'];
                    }
                    
                    $presences = Participation::whereBetween('created_at', [$monthStart, $monthEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->count();
                    
                    $programmes = Programme::whereBetween('date_programme', [$monthStart, $monthEnd])->count();
                    
                    // Nouveaux qui ont participé ce mois
                    $nouveauxParticipants = Participation::whereBetween('created_at', [$monthStart, $monthEnd]) // CORRIGÉ ICI
                        ->where('present', true)
                        ->distinct('nouveau_id')
                        ->count('nouveau_id');
                    
                    $taux = 0;
                    if ($programmes > 0 && $nouveauxParticipants > 0) {
                        $taux = round(($presences / ($nouveauxParticipants * $programmes)) * 100);
                    }
                    
                    $data[] = $taux;
                    $total += $taux;
                }
                break;
        }
        
        $average = count($data) > 0 ? round($total / count($data), 1) : 0;
        
        // Calcul de la tendance (différence entre premier et dernier point)
        $trend = 0;
        if (count($data) >= 2) {
            $first = $data[0];
            $last = $data[count($data) - 1];
            if ($first > 0) {
                $trend = round((($last - $first) / $first) * 100, 1);
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'average' => $average,
            'trend' => $trend,
        ];
    }
    
    public function getData(Request $request)
    {
        $period = $request->get('period', 'week');
        $dateRef = $request->get('date_ref', now()->format('Y-m-d'));
        
        $data = [
            'success' => true,
            'period' => $period,
            'date_ref' => $dateRef,
            'stats' => [
                'totalNouveaux' => Nouveau::count(),
                'totalAides' => User::where('role', 'aide')->count(),
                'totalProgrammes' => Programme::count(),
            ],
            'nouveauxParPeriode' => $this->calculateNouveauxParPeriode($period, $dateRef),
            'programmesParPeriode' => $this->getProgrammesParPeriode($period, $dateRef),
            'retentionData' => $this->getRealRetentionData($period, $dateRef),
            'topAides' => $this->getRealTopAides(5, $period, $dateRef),
            'summaryData' => $this->getSummaryParPeriode($period, $dateRef),
            'chartData' => $this->getRealChartData($period, $dateRef),
        ];
        
        return response()->json($data);
    }
    
    private function calculateAssignationMoyenne($totalNouveaux, $totalAides)
    {
        if ($totalAides > 0) {
            return round($totalNouveaux / $totalAides, 1);
        }
        return 0;
    }
}
