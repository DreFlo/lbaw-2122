<?php

namespace App\Policies;

use App\Models\FriendRequestNotification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendRequestNotificationPolicy
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
     * @param  \App\Models\FriendRequestNotification  $friendRequestNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FriendRequestNotification $friendRequestNotification)
    {
        return $user->admin_flag || $user == $friendRequestNotification->target();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequestNotification  $friendRequestNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FriendRequestNotification $friendRequestNotification)
    {
        return $user->admin_flag || $user == $friendRequestNotification->target();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequestNotification  $friendRequestNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FriendRequestNotification $friendRequestNotification)
    {
        return $user->admin_flag || $user == $friendRequestNotification->target();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequestNotification  $friendRequestNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FriendRequestNotification $friendRequestNotification)
    {
        return $user->admin_flag;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequestNotification  $friendRequestNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FriendRequestNotification $friendRequestNotification)
    {
        return $user->admin_flag;
    }
}
