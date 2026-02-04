<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgrammeController extends Controller
{
    public function index()
    {
        // CORRECTION : changer 'admin' en 'createur' et utiliser la relation correcte
        $programmes = Programme::with('createur')
            ->orderBy('date_programme', 'desc')
            ->paginate(10);
        
        return view('admin.programmes.index', compact('programmes'));
    }
    
    public function create()
    {
        return view('admin.programmes.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_programme' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:255',
        ]);
        
        // Combine la date et l'heure pour le champ datetime
        $dateTime = $request->date_programme . ' ' . $request->heure_debut . ':00';
        
        Programme::create([
            // CORRECTION : utiliser 'admin_id' pour correspondre au champ dans la table
            'admin_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'date_programme' => $dateTime,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'lieu' => $request->lieu,
        ]);
        
        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme créé avec succès');
    }
    
    public function show(Programme $programme)
    {
        $participations = Participation::where('programme_id', $programme->id)
            ->with('nouveau')
            ->paginate(10);
            
        // CORRECTION : utiliser 'statut' = 'present' au lieu de 'present' = true
        $stats = [
            'total' => $participations->total(),
            'presents' => $participations->where('statut', 'present')->count(),
            'absents' => $participations->where('statut', 'absent')->count(),
        ];
        
        return view('admin.programmes.show', compact('programme', 'participations', 'stats'));
    }
    
    public function edit(Programme $programme)
    {
        return view('admin.programmes.edit', compact('programme'));
    }
    
    public function update(Request $request, Programme $programme)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_programme' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:255',
        ]);
        
        // Combine la date et l'heure pour le champ datetime
        $dateTime = $request->date_programme . ' ' . $request->heure_debut . ':00';
        
        $programme->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_programme' => $dateTime,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'lieu' => $request->lieu,
        ]);
        
        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme mis à jour');
    }
    
    public function destroy(Programme $programme)
    {
        $programme->delete();
        
        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme supprimé');
    }
    
    public function participations(Programme $programme)
    {
        $participations = Participation::where('programme_id', $programme->id)
            ->with('nouveau')
            ->paginate(20);
            
        return view('admin.programmes.participations', compact('programme', 'participations'));
    }
}