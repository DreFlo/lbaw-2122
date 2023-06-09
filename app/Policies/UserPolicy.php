<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function enter(?User $user): bool
    {
        return $user === null || $user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param User $model
     * @return Response
     */
    public function view(?User $user, User $model): Response
    {
        if(optional($user)->priv_stat === 'Banned' || optional($user)->priv_stat === 'Anonymous') return Response::deny();
        elseif($model->priv_stat === 'Banned' || $model->priv_stat === 'Anonymous') return Response::deny();
        elseif ($model->priv_stat === 'Public' || optional($user)->id===$model->id) return Response::allow();
        elseif (optional($user)->isFriend($model)) return Response::allow();
        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function handleBan(User $admin, User $user): bool
    {
        return $admin->isAdmin() && !$user->isAdmin();
    }
}
