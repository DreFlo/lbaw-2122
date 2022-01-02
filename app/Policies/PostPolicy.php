<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function share(User $user, Post $post)
    {
        $policy = new UserContentPolicy();
        return $policy->view($user, $post->content) && $post->content->priv_stat === 'Public';
    }
}
