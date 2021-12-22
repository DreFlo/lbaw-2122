<?php

namespace App\Policies;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Membership $membership)
    {
        // Se o grupo for publico pode se ver?
        return $user->id === $membership->user_id || $membership->group()->isModerator($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Nao faz sentido o create , é automatico nao?
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Membership $membership)
    {
        return $user->isAdmin() || $user->id === $membership->user_id || $membership->group()->isModerator($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Membership $membership)
    {
        return $user->isAdmin() || $user->id === $membership->user_id || $membership->group()->isModerator($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Membership $membership)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Membership $membership)
    {
        return false;
    }
}
