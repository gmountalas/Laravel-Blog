<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
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
        $users = User::all();

        if ($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('There are no blogposts or users, so no comments will be added');
            return;
        }

        $commentsCount = $this->command->ask('How many comments would you like', 150);

        Comment::factory()->count($commentsCount)->make()->each(function($comment) use ($posts, $users) {
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = BlogPost::class;
            $comment->user_id = $users->random()->id;
            $comment->save();
        });

        Comment::factory()->count($commentsCount)->make()->each(function($comment) use ($users) {
            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = User::class;
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
