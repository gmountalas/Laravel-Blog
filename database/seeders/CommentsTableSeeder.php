<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Ask the user for number of comments to make and associate with blogposts, iterate over each comment,
        // use $posts variable outside of function scope, pick a random blogpost and
        // its id and then assign it to the blog_post_id foreign key and save the comment
        $posts = BlogPost::all();

        if ($posts->count() === 0) {
            $this->command->info('There are no blogposts, so no comments will be added');
            return;
        }

        $commentsCount = $this->command->ask('How many comments would you like', 150);

        Comment::factory()->count($commentsCount)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}