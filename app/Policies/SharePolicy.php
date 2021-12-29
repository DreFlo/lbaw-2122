<?php

namespace App\Policies;

use App\Models\Share;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SharePolicy
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
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Share $share)
    {
        $userContentPolicy = new UserContentPolicy();
        $postPolicy = new PostPolicy();
        return $userContentPolicy->view($user, $share->content) && $postPolicy->view($user, $share->post);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Acho que está errada porque teoricamente so quem tem acesso ao post pode dar share
        return $user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Share $share)
    {
        return $user->id === $share->content()->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Share $share)
    {
        return $user->isAdmin() || $user->id === $share->content()->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Share $share)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Share $share)
    {
        return false;
    }
}
