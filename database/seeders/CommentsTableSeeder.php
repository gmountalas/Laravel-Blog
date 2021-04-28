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
        // Make 150 comments to associate with blogposts, iterate over each comment,
        // use $posts variable outside of function scope, pick a random blogpost and
        // its id and then assign it to the blog_post_id foreign key and save the comment
        $posts = BlogPost::all();
        
        Comment::factory()->count(150)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
