<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Object of App\Models\User class
        $doe = User::factory()->johnDoe()->create();

        // Eloquent Collection
        $others = User::factory()->count(20)->create();

        // Add $doe to the Collection
        $users = $others->concat([$doe]);

        // Make 50 blogposts to associate with users, iterate over each post,
        // use $users variable outside of function scope, pick a random user and
        // his id and then assign it to the user_id foreign key and save the blogpost
        $posts = BlogPost::factory()->count(50)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        // Make 150 comments to associate with blogposts, iterate over each comment,
        // use $posts variable outside of function scope, pick a random blogpost and
        // its id and then assign it to the blog_post_id foreign key and save the comment
        $comments = Comment::factory()->count(150)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
