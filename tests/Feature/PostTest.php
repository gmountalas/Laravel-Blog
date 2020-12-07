<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoBlogPostWhenNothingDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No posts found!');
    }

    public function testSeeOneBlogPostWhereThereIsOne()
    {
        // Arrange
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Blog post content';
        $post->save();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }
    public function testStoreValid()
        {
            $parameters = [
                'title' => 'Valid title',
                'content' => "More than 10 characters"
            ];

            $this->post('/posts', $parameters)->assertStatus(302)->assertSessionHas('status');

            $this->assertEquals(session('status'),'The blog post was created!');
        }
}
