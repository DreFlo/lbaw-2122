<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\ShareNotification;
use App\Models\Tag;
use App\Models\TagNotification;
use App\Models\User;
use App\Models\UserContent;
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
        User::class => UserPolicy::class,
        Tag::class => TagPolicy::class,
        TagNotification::class => TagNotificationPolicy::class,
        ShareNotification::class => ShareNotificationPolicy::class,
        Post::class => PostPolicy::class,
        Share::class => SharePolicy::class
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
        Gate::define('view-post', [PostPolicy::class, 'view']);
        Gate::define('view-share', [SharePolicy::class, 'view']);
    }
}
