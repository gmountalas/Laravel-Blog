<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\User;
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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate to check if a user is Authorized to update a blogpost
        // use it in the PostsController
        Gate::define('update-post', function(User $user,BlogPost $post) {
            return $user->id == $post->user_id;
        });

        // Gate to check if a user is Authorized to delete a blogpost
        // use it in the PostsController
        Gate::define('delete-post', function(User $user,BlogPost $post) {
            return $user->id == $post->user_id;
        });

        // overriding / intercepting the Gate check to allow actions for
        // users with is_admin field = 1 (true)
        Gate::before(function(User $user, $ability) {
            if ($user->is_admin && in_array($ability, ['update-post'])) {
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
