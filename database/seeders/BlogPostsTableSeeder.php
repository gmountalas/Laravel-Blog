<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make 50 blogposts to associate with users, iterate over each post,
        // use $users variable outside of function scope, pick a random user and
        // his id and then assign it to the user_id foreign key and save the blogpost
        $users = User::all();
        
        $commentsCount = $this->command->ask('How many blog posts would you like', 50);

        BlogPost::factory()->count($commentsCount)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
