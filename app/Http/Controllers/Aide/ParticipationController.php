<?php

namespace App\Http\Controllers\Aide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nouveau;
use App\Models\Programme;
use App\Models\Participation;

class ParticipationController extends Controller
{
    /**
     * Afficher la liste des programmes disponibles
     */
    public function programmes(Nouveau $nouveau = null)
    {
        $user = Auth::user();
        
        // MODIFIÉ : Récupère TOUS les programmes (3 derniers mois + futurs)
        $programmes = Programme::where('date_programme', '>=', now()->subMonths(3))
            ->orWhere('date_programme', '>=', now())
            ->orderBy('date_programme', 'desc')
            ->paginate(15);
        
        // Récupère les nouveaux de l'aide
        $nouveaux = Nouveau::where('aide_id', $user->id)->get();
        
        // Si un nouveau est spécifié, vérifie qu'il appartient à l'aide
        $nouveauSelectionne = null;
        if ($nouveau) {
            if ($nouveau->aide_id !== $user->id) {
                abort(403, 'Accès non autorisé à ce nouveau');
            }
            $nouveauSelectionne = $nouveau;
        }
        
        return view('aide.participations.programmes', compact('programmes', 'nouveaux', 'nouveauSelectionne'));
    }
    
    /**
     * Marquer présence/absence pour un nouveau
     */
    public function marquer(Request $request, Nouveau $nouveau, Programme $programme)
    {
        // Vérification d'accès
        if ($nouveau->aide_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('aide.participations.marquer', compact('nouveau', 'programme'));
    }
    
    /**
     * Enregistrer la présence/absence
     */
    public function enregistrer(Request $request)
    {
        $request->validate([
            'nouveau_id' => 'required|exists:nouveaux,id',
            'programme_id' => 'required|exists:programmes,id',
            'present' => 'required|boolean',
            'motif_absence' => 'nullable|string|max:500',
        ]);
        
        // Vérifier que le nouveau appartient à l'aide
        $nouveau = Nouveau::findOrFail($request->nouveau_id);
        if ($nouveau->aide_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Créer ou mettre à jour la participation
        $participation = Participation::updateOrCreate(
            [
                'nouveau_id' => $request->nouveau_id,
                'programme_id' => $request->programme_id,
                'aide_id' => Auth::id(),
            ],
            [
                'present' => $request->present,
                'motif_absence' => $request->present ? null : $request->motif_absence,
                'enregistre_le' => now(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Participation enregistrée avec succès.',
            'participation' => $participation
        ]);
    }
    
    /**
     * Historique des participations avec filtre timeframe
     */
    public function historique(Request $request, Nouveau $nouveau)
    {
        // Vérification d'accès
        if ($nouveau->aide_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Détermine le timeframe (semaine/mois)
        $timeframe = $request->get('timeframe', 'semaine');
        
        if ($timeframe === 'semaine') {
            $dateDebut = now()->startOfWeek();
            $dateFin = now()->endOfWeek();
        } else {
            $dateDebut = now()->startOfMonth();
            $dateFin = now()->endOfMonth();
        }
        
        // Récupère les participations
        $participations = Participation::where('nouveau_id', $nouveau->id)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->with('programme')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => $participations->count(),
            'present' => $participations->where('present', true)->count(),
            'absent' => $participations->where('present', false)->count(),
            'taux' => $participations->count() > 0 
                ? round(($participations->where('present', true)->count() / $participations->count()) * 100, 1)
                : 0,
        ];
        
        return view('aide.participations.historique', compact(
            'nouveau', 'participations', 'stats', 'timeframe'
        ));
    }
    
    /**
     * AJOUTÉ : Récupère les statistiques d'un nouveau (API)
     */
    public function stats(Nouveau $nouveau)
    {
        // Vérification d'accès
        if ($nouveau->aide_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }
        
        $total = $nouveau->participations()->count();
        $presences = $nouveau->participations()->where('present', true)->count();
        $taux = $total > 0 ? round(($presences / $total) * 100, 1) : 0;
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $total,
                'presences' => $presences,
                'taux' => $taux
            ]
        ]);
    }
    
    /**
     * AJOUTÉ : Récupère les participations récentes d'un nouveau (API)
     */
    public function recentes(Nouveau $nouveau)
    {
        // Vérification d'accès
        if ($nouveau->aide_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }
        
        $participations = $nouveau->participations()
            ->with('programme')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($participation) {
                return [
                    'programme_titre' => $participation->programme->titre ?? 'Programme',
                    'present' => $participation->present,
                    'motif_absence' => $participation->motif_absence,
                    'date_formatee' => $participation->created_at->format('d/m/Y H:i')
                ];
            });
        
        return response()->json([
            'success' => true,
            'participations' => $participations
        ]);
    }
}