<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nouveau_id', 
        'programme_id', 
        'present',
        'statut', 
        'motif_absence', 
        'marque_par',
        'aide_id',
        'enregistre_le' // AJOUTÉ
    ];
    
    protected $casts = [
        'statut' => 'string',
        'present' => 'boolean',
        'enregistre_le' => 'datetime', // AJOUTÉ CETTE LIGNE
    ];
    
    public function nouveau()
    {
        return $this->belongsTo(Nouveau::class, 'nouveau_id');
    }
    
    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }
    
    public function marquePar()
    {
        return $this->belongsTo(User::class, 'marque_par');
    }
    
    public function aide()
    {
        return $this->belongsTo(User::class, 'aide_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'aide_id');
    }
}