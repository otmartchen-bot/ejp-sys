<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // CORRIGÉ: admin_id -> created_by
    public function programmesCrees()
    {
        return $this->hasMany(Programme::class, 'created_by');
    }

    public function nouveaux()
    {
        return $this->hasMany(Nouveau::class, 'aide_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAide()
    {
        return $this->role === 'aide';
    }
}