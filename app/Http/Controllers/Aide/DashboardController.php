<?php

namespace App\Http\Controllers\Aide;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Nouveau;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Récupère les nouveaux avec leur dernier statut
        $nouveaux = Nouveau::where('aide_id', $user->id)
            ->with(['participations' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->get();
        
        // Calcule le statut pour chaque nouveau
        foreach ($nouveaux as $nouveau) {
            $nouveau->statut = $nouveau->getStatutAttribute();
        }
        
        return view('aide.dashboard', [
            'user' => $user,
            'nouveaux' => $nouveaux
        ]);
    }
}