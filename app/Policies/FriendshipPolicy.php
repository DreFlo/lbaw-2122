<?php

namespace App\Policies;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendshipPolicy
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
        return $user->admin_flag;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Friendship $friendship)
    {
        return $user->admin_flag || $user->id === $friendship->user_1 || $user->id === $friendship->user_2;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Friendship $friendship)
    {
        return $user->admin_flag || $user->id === $friendship->user_1 || $user->id === $friendship->user_2;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Friendship $friendship)
    {
        return $user->admin_flag || $user->id === $friendship->user_1 || $user->id === $friendship->user_2;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Friendship $friendship)
    {
        return $user->admin_flag;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Friendship $friendship)
    {
        return $user->admin_flag;
    }
}
