<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Nouveau;
use Illuminate\Auth\Access\Response;

class NouveauPolicy
{
    public function view(User $user, Nouveau $nouveau): bool
    {
        // Un aide ne peut voir que SES nouveaux
        return $user->role === 'aide' && $nouveau->aide_id === $user->id;
    }

    public function update(User $user, Nouveau $nouveau): bool
    {
        // Un aide ne peut modifier que SES nouveaux
        return $user->role === 'aide' && $nouveau->aide_id === $user->id;
    }

    public function delete(User $user, Nouveau $nouveau): bool
    {
        // Un aide ne peut supprimer que SES nouveaux
        return $user->role === 'aide' && $nouveau->aide_id === $user->id;
    }
}