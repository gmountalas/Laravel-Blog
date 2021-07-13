<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\User;
use App\Policies\BlogPostPolicy;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => BlogPostPolicy::class,
        'App\Models\User' => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function ($user) {
            return $user->is_admin;
        });

        // Gate to check if a user is Authorized to update a blogpost
        // use it in the PostsController
        // Gate::define('update-post', function(User $user,BlogPost $post) {
        //     return $user->id == $post->user_id;
        // });

        // Gate to check if a user is Authorized to delete a blogpost
        // use it in the PostsController
        // Gate::define('delete-post', function(User $user,BlogPost $post) {
        //     return $user->id == $post->user_id;
        // });

        // Use the BlogPostPolicy to check the a user's ability to update
        // and delete a blogPost. REPLACES THE ABOVE
        // Gate::define('posts.update', [BlogPostPolicy::class, 'update']);
        // Gate::define('posts.delete', [BlogPostPolicy::class, 'delete']);


        // Gate::resource('posts', BlogPostPolicy::class);
        // posts.create, posts.view, posts.update, posts.delete

        // overriding / intercepting the Gate check to allow actions for
        // users with is_admin field = 1 (true)
        Gate::before(function(User $user, $ability) {
            if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
                return true;
            }
        });
        // Called after the checks, can modify the result of the Gate check
        // Gate::after(function(User $user, $ability, $result) {
        //     if ($user->is_admin) {
        //         return true;
        //     }
        // });
    }
}
