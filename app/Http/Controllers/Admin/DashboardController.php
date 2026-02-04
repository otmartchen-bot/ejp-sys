<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nouveau;
use App\Models\Programme;
use App\Models\Participation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Statistiques principales
        $stats = [
            'total_aides' => User::where('role', 'aide')->count(),
            'total_nouveaux' => Nouveau::count(),
            'total_programmes' => Programme::count(),
            'nouveaux_ce_mois' => Nouveau::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        // Taux de rétention (30 derniers jours)
        $totalNouveaux = $stats['total_nouveaux'];
        $nouveauxActifs = Nouveau::whereHas('participations', function($query) {
            $query->where('present', true)
                  ->where('created_at', '>=', now()->subDays(30));
        })->count();
        
        $retentionRate = $totalNouveaux > 0 ? round(($nouveauxActifs / $totalNouveaux) * 100, 1) : 0;
        
        // Données pour le graphique (7 derniers jours)
        $evolutionData = [];
        $jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Participation::whereDate('created_at', $date)->count();
            $evolutionData[$jours[6-$i]] = $count;
        }
        
        return view('admin.dashboard', compact('stats', 'retentionRate', 'evolutionData'));
    }
}