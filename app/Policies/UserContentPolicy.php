<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Models\UserContent;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserContentPolicy
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

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param UserContent $userContent
     * @return Response
     */
    public function view(?User $user, UserContent $userContent): Response
    {
        if (optional($user)->isAdmin()) {
            return Response::allow();
        }
        elseif ($userContent->priv_stat === 'Public') {
            return Response::allow();
        }
        elseif ($userContent->priv_stat === 'Private') {
            if (optional($user)->id === $userContent->creator_id) return Response::allow();
            elseif (optional($user)->isFriend($userContent->creator)) return Response::allow();
            elseif ($userContent->inGroup() && optional($user, function($user, $userContent) {return $userContent->group->isMember($user);}))
                return Response::allow();
        }
        return Response::deny('Not authorized to view content');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->priv_stat !== 'Banned' && $user->priv_stat !== 'Anonymous';
    }

    public function createInGroup(User $user, Group $group): bool
    {
        return $group->isMember($user) && $this->create($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param UserContent $userContent
     * @return bool
     */
    public function update(User $user, UserContent $userContent): bool
    {
        return $user->id === $userContent->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param UserContent $userContent
     * @return bool
     */
    public function delete(User $user, UserContent $userContent): bool
    {
        return $user->isAdmin() || $user->id === $userContent->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param UserContent $userContent
     * @return bool
     */
    public function restore(User $user, UserContent $userContent): bool
    {
        return ($user->isAdmin() && $userContent->priv_stat === 'Banned') ||
            ($user->id === $userContent->creator_id && $userContent->priv_stat === 'Anonymous');
    }
}
