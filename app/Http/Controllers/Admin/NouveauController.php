<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nouveau;
use App\Models\User;
use App\Models\Participation;
use Illuminate\Http\Request;

class NouveauController extends Controller
{
    public function index()
    {
        $nouveaux = Nouveau::with('aide')
            ->withCount('participations')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.nouveaux.index', compact('nouveaux'));
    }
    
    public function create()
    {
        $aides = User::where('role', 'aide')->get();
        return view('admin.nouveaux.create', compact('aides'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:nouveaux,email',
            'profession' => 'required|string|max:200',
            'fij' => 'required|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'date_enregistrement' => 'required|date',
            'aide_id' => 'nullable|exists:users,id',
        ]);
        
        Nouveau::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'profession' => $request->profession,
            'fij' => $request->fij,
            'telephone' => $request->telephone,
            'date_enregistrement' => $request->date_enregistrement,
            'aide_id' => $request->aide_id,
        ]);
        
        return redirect()->route('admin.nouveaux.index')
            ->with('success', 'Nouveau créé avec succès');
    }
    
    public function show(Nouveau $nouveau)
    {
        // Charger les participations avec toutes les relations
        $participations = $nouveau->participations()
            ->with(['programme', 'marquePar', 'aide'])
            ->orderBy('enregistre_le', 'desc')
            ->get();
        
        // Calculer les statistiques CORRECTEMENT
        $totalParticipations = $participations->count();
        
        // IMPORTANT : Utiliser 'present' (booléen) au lieu de 'statut' (string)
        $presences = $participations->where('present', true)->count();
        $absences = $participations->where('present', false)->count();
        
        $taux = $totalParticipations > 0 ? round(($presences / $totalParticipations) * 100, 1) : 0;
        
        // Déterminer le statut
        if ($totalParticipations === 0) {
            $statut = ['label' => 'inactif', 'couleur' => 'red'];
        } elseif ($taux >= 80) {
            $statut = ['label' => 'actif', 'couleur' => 'green'];
        } elseif ($taux >= 50) {
            $statut = ['label' => 'moyen', 'couleur' => 'yellow'];
        } else {
            $statut = ['label' => 'inactif', 'couleur' => 'red'];
        }
        
        return view('admin.nouveaux.show', compact(
            'nouveau', 
            'participations', 
            'totalParticipations', 
            'presences', 
            'absences', 
            'taux', 
            'statut'
        ));
    }
    
    public function edit(Nouveau $nouveau)
    {
        $aides = User::where('role', 'aide')->get();
        return view('admin.nouveaux.edit', compact('nouveau', 'aides'));
    }
    
    public function update(Request $request, Nouveau $nouveau)
    {
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:nouveaux,email,' . $nouveau->id,
            'profession' => 'required|string|max:200',
            'fij' => 'required|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'date_enregistrement' => 'required|date',
            'aide_id' => 'nullable|exists:users,id',
        ]);
        
        $nouveau->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'profession' => $request->profession,
            'fij' => $request->fij,
            'telephone' => $request->telephone,
            'date_enregistrement' => $request->date_enregistrement,
            'aide_id' => $request->aide_id,
        ]);
        
        return redirect()->route('admin.nouveaux.show', $nouveau)
            ->with('success', 'Nouveau mis à jour');
    }
    
    public function destroy(Nouveau $nouveau)
    {
        $nouveau->participations()->delete();
        $nouveau->delete();
        
        return redirect()->route('admin.nouveaux.index')
            ->with('success', 'Nouveau supprimé avec succès');
    }
    
    // Méthode supplémentaire pour corriger les données
    public function corrigerParticipations($id)
    {
        $nouveau = Nouveau::findOrFail($id);
        
        // Vérifier et corriger les participations
        foreach ($nouveau->participations as $participation) {
            // Si 'statut' est défini mais pas 'present'
            if (!is_null($participation->statut) && is_null($participation->present)) {
                $present = ($participation->statut == 'present');
                $participation->update([
                    'present' => $present,
                    'enregistre_le' => $participation->enregistre_le ?? $participation->created_at
                ]);
            }
        }
        
        return redirect()->route('admin.nouveaux.show', $nouveau)
            ->with('success', 'Participations corrigées');
    }
}