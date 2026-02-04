<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nouveau extends Model
{
    // Spécifie le nom de la table
    protected $table = 'nouveaux';
    
    protected $fillable = [
        'aide_id', 'nom', 'prenom', 'email', 
        'profession', 'fij', 'date_enregistrement'
    ];
    
    // Relation avec l'aide
    public function aide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aide_id');
    }
    
    // Relation avec les participations (AJOUTE ÇA !)
    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class, 'nouveau_id');
    }
    
    // Accessor pour le nom complet
    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
    

    // Accessor pour calculer le statut
    public function getStatutAttribute(): array
    {
        $total = $this->participations()->count();
        
        if ($total === 0) {
            return [
                'label' => 'inactif',
                'pourcentage' => 0,
                'couleur' => 'red'
            ];
        }
        
        $presences = $this->participations()->where('present', true)->count();
        $taux = round(($presences / $total) * 100, 1);
        
        if ($taux >= 80) {
            return [
                'label' => 'actif',
                'pourcentage' => $taux,
                'couleur' => 'green'
            ];
        } elseif ($taux >= 50) {
            return [
                'label' => 'moyen',
                'pourcentage' => $taux,
                'couleur' => 'yellow'
            ];
        }
        
        return [
            'label' => 'inactif',
            'pourcentage' => $taux,
            'couleur' => 'red'
        ];
    }
}