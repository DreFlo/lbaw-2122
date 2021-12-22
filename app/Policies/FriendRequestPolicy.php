<?php

namespace App\Policies;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendRequestPolicy
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
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FriendRequest $friendRequest)
    {
        if($friendRequest->req_stat === 'Pending') return Response::allow();

        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous') return Response::allow();

        return Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FriendRequest $friendRequest)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FriendRequest $friendRequest)
    {
        return ($user == $friendRequest->target || $user == $friendRequest->requester);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FriendRequest $friendRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FriendRequest $friendRequest)
    {
        return ($user == $friendRequest->target || $user == $friendRequest->requester);
    }
}
