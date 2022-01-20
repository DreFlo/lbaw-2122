<?php

namespace App\Policies;

use App\Models\GroupRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupRequestPolicy
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
     * @param  \App\Models\GroupRequest  $groupRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, GroupRequest $groupRequest)
    {
        return in_array($user, $groupRequest->group->moderators);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    public function request(User $user) {
        return $user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupRequest  $groupRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, GroupRequest $groupRequest)
    {
        return in_array($user, $groupRequest->group->moderators) || $user->admin_flag;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupRequest  $groupRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, GroupRequest $groupRequest)
    {
        return in_array($user, $groupRequest->group->moderators) || $user->admin_flag;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupRequest  $groupRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, GroupRequest $groupRequest)
    {
        return in_array($user, $groupRequest->group->moderators) || $user->admin_flag;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupRequest  $groupRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, GroupRequest $groupRequest)
    {
        return $user->admin_flag;
    }
}
