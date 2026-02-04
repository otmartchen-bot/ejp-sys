<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nouveau;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AideController extends Controller
{
   public function index()
{
    // AJOUTE withCount('nouveaux') pour avoir $aide->nouveaux_count
    $aides = User::where('role', 'aide')
        ->withCount('nouveaux') // TRÈS IMPORTANT !
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    return view('admin.aides.index', compact('aides'));
}
    
    public function create()
    {
        return view('admin.aides.create');
    }
    
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    $aide = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // ← CORRIGÉ ICI
        'role' => 'aide',
    ]);
    
    return redirect()->route('admin.aides.index')
        ->with('success', 'Aide créé avec succès');
}
    
  public function show(User $user)
{
    // Version 1 : Si tu as la colonne 'present' (booléen)
    $nouveaux = $user->nouveaux()
        ->withCount(['participations' => function($query) {
            // Essayer d'abord avec 'present' (booléen)
            $query->where('present', true);
        }])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Version alternative si l'erreur persiste
    // $nouveaux = $user->nouveaux()
    //     ->withCount('participations') // Juste compter toutes les participations
    //     ->orderBy('created_at', 'desc')
    //     ->limit(5)
    //     ->get();
    
    return view('admin.aides.show', compact('user', 'nouveaux'));
}
    
    public function edit(User $user)
    {
        return view('admin.aides.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.aides.show', $user)
            ->with('success', 'Aide mis à jour avec succès');
    }
    
    public function destroy(User $user)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        // Réassigner les nouveaux à null avant de supprimer l'aide
        Nouveau::where('aide_id', $user->id)->update(['aide_id' => null]);
        
        $user->delete();
        
        return redirect()->route('admin.aides.index')
            ->with('success', 'Aide supprimé avec succès');
    }
    
    public function nouveaux(User $user)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        $nouveaux = $user->nouveaux()
            ->withCount(['participations' => function($query) {
                $query->where('statut', 'present');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.aides.nouveaux', compact('user', 'nouveaux'));
    }
    
    public function assignNouveau(Request $request, User $user)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        $request->validate([
            'nouveau_id' => 'required|exists:nouveaux,id',
        ]);
        
        $nouveau = Nouveau::findOrFail($request->nouveau_id);
        
        // Vérifie si le nouveau n'est pas déjà assigné à cet aide
        if ($nouveau->aide_id == $user->id) {
            return redirect()->back()
                ->with('warning', 'Ce nouveau est déjà assigné à cet aide');
        }
        
        $nouveau->update(['aide_id' => $user->id]);
        
        return redirect()->route('admin.aides.show', $user)
            ->with('success', "{$nouveau->prenom} {$nouveau->nom} a été assigné à cet aide");
    }
    
    public function removeNouveau(User $user, $nouveauId)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        $nouveau = Nouveau::findOrFail($nouveauId);
        
        // Vérifie que le nouveau est bien assigné à cet aide
        if ($nouveau->aide_id != $user->id) {
            return redirect()->back()
                ->with('error', 'Ce nouveau n\'est pas assigné à cet aide');
        }
        
        $nouveau->update(['aide_id' => null]);
        
        return redirect()->back()
            ->with('success', "{$nouveau->prenom} {$nouveau->nom} a été retiré de cet aide");
    }
    
    public function resetPassword(Request $request, User $user)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        $request->validate([
            'temp_password' => 'nullable|string|min:6',
        ]);
        
        // Génère un mot de passe temporaire ou utilise celui fourni
        $password = $request->temp_password ?? Str::random(8);
        
        // Met à jour le mot de passe
        $user->update([
            'password' => Hash::make($password)
        ]);
        
        // Optionnel : envoyer un email avec le nouveau mot de passe
        // Mail::to($user->email)->send(new PasswordResetMail($password));
        
        return redirect()->route('admin.aides.show', $user)
            ->with('success', "Mot de passe réinitialisé avec succès. Le nouveau mot de passe est : <strong>{$password}</strong>");
    }
    
    public function toggleStatus(User $user)
    {
        // Vérifie que c'est bien un aide
        if ($user->role !== 'aide') {
            abort(404, 'Utilisateur non trouvé');
        }
        
        // Active/désactive le compte de l'aide
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        
        return redirect()->back()
            ->with('success', "Compte {$user->name} {$status} avec succès");
    }
}