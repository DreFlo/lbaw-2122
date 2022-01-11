<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Post;
use App\Models\ShareNotification;
use App\Models\Tag;
use App\Models\TagNotification;
use App\Models\User;
use App\Models\UserContent;
use App\Policies\GroupPolicy;
use App\Policies\PostPolicy;
use App\Policies\ShareNotificationPolicy;
use App\Policies\SharePolicy;
use App\Policies\TagNotificationPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserContentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserContent::class => UserContentPolicy::class,
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Tag::class => TagPolicy::class,
        TagNotification::class => TagNotificationPolicy::class,
        ShareNotification::class => ShareNotificationPolicy::class,
        Group::class => GroupPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-content', [UserContentPolicy::class, 'view']);
        Gate::define('create-content', [UserContentPolicy::class, 'create']);
        Gate::define('delete-content', [UserContentPolicy::class, 'delete']);
        Gate::define('update-content', [UserContentPolicy::class, 'update']);
        Gate::define('restore-content', [UserContentPolicy::class, 'restore']);
        Gate::define('viewAny-content', [UserContentPolicy::class, 'viewAny']);
        Gate::define('share-post', [PostPolicy::class, 'share']);
        Gate::define('view-user', [UserPolicy::class, 'view']);
        Gate::define('viewAny-user', [UserPolicy::class, 'viewAny']);
        Gate::define('viewAny-group', [GroupPolicy::class, 'viewAny']);
        Gate::define('delete-group', [GroupPolicy::class, 'delete']);
        Gate::define('handleBan-user', [UserPolicy::class, 'handleBan']);
        Gate::define('createIn-group', [GroupPolicy::class, 'createInGroup']);
    }
}
