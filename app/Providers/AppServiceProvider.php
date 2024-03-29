<?php

namespace App\Providers;

use App\Contracts\CounterContract;
use App\Http\ViewComposers\ActivityComposer;
use App\Http\Resources\Comment as CommentResource;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);

        // Register the BlogPostObserver to be used when a Blogpost is created etc.
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        // Register the Counter Service to the Service Container via the Service Provider
        $this->app->singleton(Counter::class, function ($app) {
            return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                env('COUNTER_TIMEOUT')
            );
        });

        $this->app->bind(
            CounterContract::class,
            Counter::class
        );

        // To return a resourse without the 'data' key wrapping the json response
        // CommentResource::withoutWrapping();
        JsonResource::withoutWrapping();

        // The Services inside the Service Container might need primitive values
        // or Classes, which are also Services, or both. In this case since we 
        // only need a primitive value instead of a singleton we can tell Laravel
        // that whenever it needs to inject a Class of a certain type it should pass
        // a specific value for a specific variable. Replaces the above singleton
        // $this->app->when(Counter::class)
        //     ->needs('$timeout')
        //     ->give(env('COUNTER_TIMEOUT'));
    }
}
