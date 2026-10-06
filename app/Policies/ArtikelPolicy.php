<?php

namespace App\Policies;

use App\Models\Artikel;
use App\Models\User;

class ArtikelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Artikel $artikel): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Artikel $artikel): bool
    {
        return (bool) ($user->is_admin ?? false) || ($artikel->user_id !== null && $user->id === $artikel->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Artikel $artikel): bool
    {
        return (bool) ($user->is_admin ?? false) || ($artikel->user_id !== null && $user->id === $artikel->user_id);
    }
}
