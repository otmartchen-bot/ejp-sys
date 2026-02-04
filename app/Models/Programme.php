<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'admin_id', // Garder 'admin_id' car c'est le nom dans le contrôleur et la base
        'titre',
        'description',
        'date_programme',
        'heure_debut',
        'heure_fin',
        'lieu'
    ];
    
    protected $casts = [
        'date_programme' => 'datetime',
    ];
    
    // CORRECTION : utiliser 'admin_id' pour correspondre au champ dans la table
    public function createur()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    // AJOUT : Relation 'admin' pour compatibilité avec les anciennes vues
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    // AJOUTÉ : Relation avec les participations
    public function participations()
    {
        return $this->hasMany(Participation::class);
    }
    
    // Accessor pour obtenir juste la date (sans l'heure)
    public function getDateOnlyAttribute()
    {
        return $this->date_programme ? $this->date_programme->format('Y-m-d') : null;
    }
    
    // Accessor pour obtenir juste l'heure (sans la date)
    public function getHeureOnlyAttribute()
    {
        return $this->date_programme ? $this->date_programme->format('H:i') : null;
    }
}