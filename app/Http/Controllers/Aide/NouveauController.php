<?php

namespace App\Http\Controllers\Aide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Nouveau;
use App\Models\Participation;
use App\Models\Programme;

class NouveauController extends Controller
{
    /**
     * Vérifie que l'aide a le droit d'accéder à ce nouveau
     */
    private function checkAccess(Nouveau $nouveau)
{
    // Vérifie que le nouveau appartient à l'aide connecté
    // CORRECTION: Convertir en int pour éviter les problèmes de type
    if ((int) $nouveau->aide_id !== (int) Auth::id()) {
        abort(403, 'Accès non autorisé.');
    }
}

    /**
     * Liste des nouveaux (vue principale)
     */
    public function index()
    {
        $user = Auth::user();
        
        // IMPORTANT: Utilise paginate() au lieu de get()
        $nouveaux = Nouveau::where('aide_id', $user->id)
            ->withCount([
                'participations as total_participations',
                'participations as presences_count' => function($query) {
                    $query->where('present', true);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // ← CHANGER get() EN paginate(10)
        
        // Calcule le statut pour chaque nouveau
        foreach ($nouveaux as $nouveau) {
            $total = $nouveau->total_participations;
            $presences = $nouveau->presences_count;
            
            if ($total === 0) {
                $nouveau->statut = [
                    'label' => 'inactif',
                    'pourcentage' => 0,
                    'couleur' => 'red'
                ];
            } else {
                $taux = round(($presences / $total) * 100, 1);
                
                if ($taux >= 80) {
                    $nouveau->statut = [
                        'label' => 'actif',
                        'pourcentage' => $taux,
                        'couleur' => 'green'
                    ];
                } elseif ($taux >= 50) {
                    $nouveau->statut = [
                        'label' => 'moyen',
                        'pourcentage' => $taux,
                        'couleur' => 'yellow'
                    ];
                } else {
                    $nouveau->statut = [
                        'label' => 'inactif',
                        'pourcentage' => $taux,
                        'couleur' => 'red'
                    ];
                }
            }
            
            // Ajoute le nom complet
            $nouveau->full_name = $nouveau->prenom . ' ' . $nouveau->nom;
        }
        
        return view('aide.nouveaux.index', compact('nouveaux'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('aide.nouveaux.create');
    }

    /**
     * Enregistrer un nouveau
     */
    public function store(Request $request)
    {
        // LOGS POUR DEBUG
        Log::info('=== TENTATIVE AJOUT NOUVEAU ===');
        Log::info('Utilisateur ID: ' . Auth::id());
        Log::info('Données reçues: ' . json_encode($request->all()));

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:nouveaux,email',
            'profession' => 'required|string|max:255',
            'fij' => 'required|string|max:100',
            'date_enregistrement' => 'required|date',
        ]);

        $validated['aide_id'] = Auth::id();

        $nouveau = Nouveau::create($validated);

        Log::info('=== SUCCÈS : NOUVEAU CRÉÉ ID ' . $nouveau->id . ' ===');

        return redirect()->route('aide.nouveaux.index')
            ->with('success', 'Nouveau ajouté avec succès.');
    }

    /**
     * Afficher un nouveau (vue détaillée)
     */
    public function show(Nouveau $nouveau)
    {
        // Vérification de sécurité
        $this->checkAccess($nouveau);

        // Charge les relations nécessaires
        $nouveau->load(['participations' => function($query) {
            $query->with('programme')->orderBy('created_at', 'desc');
        }]);

        // Calcule les statistiques
        $stats = [
            'total_participations' => $nouveau->participations->count(),
            'presences' => $nouveau->participations->where('present', true)->count(),
            'absences' => $nouveau->participations->where('present', false)->count(),
        ];

        return view('aide.nouveaux.show', compact('nouveau', 'stats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Nouveau $nouveau)
    {
        $this->checkAccess($nouveau);
        return view('aide.nouveaux.edit', compact('nouveau'));
    }

    /**
     * Mettre à jour un nouveau
     */
    public function update(Request $request, Nouveau $nouveau)
    {
        $this->checkAccess($nouveau);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:nouveaux,email,' . $nouveau->id,
            'profession' => 'required|string|max:255',
            'fij' => 'required|string|max:100',
        ]);

        $nouveau->update($validated);

        return redirect()->route('aide.nouveaux.show', $nouveau)
            ->with('success', 'Nouveau modifié avec succès.');
    }

    /**
     * Supprimer un nouveau
     */
    public function destroy(Nouveau $nouveau)
    {
        $this->checkAccess($nouveau);
        
        $nouveau->delete();

        return redirect()->route('aide.nouveaux.index')
            ->with('success', 'Nouveau supprimé avec succès.');
    }

    /**
     * Page Détails (Informations personnelles + Historique)
     */
    public function details(Nouveau $nouveau)
    {
        $this->checkAccess($nouveau);

        // Charge les participations avec les programmes
        $nouveau->load(['participations' => function($query) {
            $query->with('programme')->orderBy('created_at', 'desc');
        }]);

        return view('aide.nouveaux.details', compact('nouveau'));
    }

    /**
     * Historique des participations
     */
    public function historique(Request $request, Nouveau $nouveau)
{
    $this->checkAccess($nouveau);
    
    // Récupère les paramètres de filtrage
    $periode = $request->input('periode', 'tous');
    $statut = $request->input('statut', 'tous');
    
    // Construction de la requête
    $query = $nouveau->participations()->with('programme');
    
    // Filtre par période
    if ($periode !== 'tous') {
        if ($periode === 'semaine') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($periode === 'mois') {
            $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        } elseif ($periode === 'trimestre') {
            $query->whereBetween('created_at', [
                now()->startOfQuarter(),
                now()->endOfQuarter()
            ]);
        }
    }
    
    // Filtre par statut
    if ($statut !== 'tous') {
        if ($statut === 'present') {
            $query->where('present', true);
        } elseif ($statut === 'absent') {
            $query->where('present', false);
        }
    }
    
    // Pagination
    $participations = $query->orderBy('created_at', 'desc')->paginate(10);
    
    return view('aide.nouveaux.details', compact('nouveau', 'participations', 'periode', 'statut'));
}

    // Ajoute cette méthode dans NouveauController.php
public function historiqueFiltre(Request $request, Nouveau $nouveau)
{
    $this->checkAccess($nouveau);
    
    // Récupère les paramètres de filtrage
    $periode = $request->input('periode', 'tous');
    $statut = $request->input('statut', 'tous');
    
    // Construction de la requête
    $query = $nouveau->participations()->with('programme');
    
    // Filtre par période
    if ($periode !== 'tous') {
        if ($periode === 'semaine') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($periode === 'mois') {
            $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        }
    }
    
    // Filtre par statut
    if ($statut !== 'tous') {
        if ($statut === 'present') {
            $query->where('present', true);
        } elseif ($statut === 'absent') {
            $query->where('present', false);
        }
    }
    
    // Pagination
    $participations = $query->orderBy('created_at', 'desc')->paginate(10);
    
    return view('aide.nouveaux.historique', compact('nouveau', 'participations', 'periode', 'statut'));
}

    /**
     * Marquer présence/absence (à implémenter plus tard)
     */
    public function marquerPresence(Request $request, Nouveau $nouveau)
    {
        $this->checkAccess($nouveau);

        // Logique à implémenter
        return redirect()->back()
            ->with('success', 'Présence enregistrée.');
    }
    
    /**
     * AJOUTÉ : Récupère les statistiques d'un nouveau (pour le modal de suppression)
     */
    public function stats(Nouveau $nouveau)
    {
        // Vérification de sécurité
        $this->checkAccess($nouveau);
        
        return response()->json([
            'success' => true,
            'email' => $nouveau->email,
            'participations' => $nouveau->participations()->count(),
            'created_at' => $nouveau->created_at->format('d/m/Y'),
            'profession' => $nouveau->profession,
            'fij' => $nouveau->fij
        ]);
    }
}