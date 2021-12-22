<?php

namespace App\Policies;

use App\Models\ShareNotification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShareNotificationPolicy
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
     * @param  \App\Models\ShareNotification  $shareNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ShareNotification $shareNotification)
    {
        return $user->id === $shareNotification->share->post->creator->id || $user->isAdmin();
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
     * @param  \App\Models\ShareNotification  $shareNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ShareNotification $shareNotification)
    {
        return $user->id === $shareNotification->share->post->creator->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ShareNotification  $shareNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ShareNotification $shareNotification)
    {
        return $user->id === $shareNotification->share->post->creator->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ShareNotification  $shareNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ShareNotification $shareNotification)
    {
        return $user->id === $shareNotification->share->post->creator->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ShareNotification  $shareNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ShareNotification $shareNotification)
    {
        return false;
    }
}
