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

    private function createDummyBlogPost(): BlogPost
    {
        // Create BlogPost with dummy data
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Blog post content';
        $post->save();

        return $post;
    }

    public function testSeeOneBlogPostWhereThereIsOne()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');
        // Assert for no comments message directly adter blogpost creation
        $response->assertSeeText('No comments yet!');

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

    // Testing for failure
    public function testStoreFail()
    {
        // Parameters don't pass the validation rquirements
        $parameters = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->post('/posts', $parameters)->assertStatus(302)->assertSessionHas('errors');
        
        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Assert - Verify the blogpost exists inside the database
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        // The parameters to modify the blogpost with
        $parameters = [
            'title' => 'A new named title',
            'content' => 'Content that is valid'
        ];

        // Put request to modify the blog post with dummy parameters
        $this->put("/posts/{$post->id}", $parameters)->assertStatus(302)->assertSessionHas('status');

        // Assert that the status is there
        $this->assertEquals(session('status'),'Blog post was updated!');

        // Assert that the blogpost doesn't contain the original dummy title and content
        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());

        // Assert that the blogpost contains the new title
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title'
        ]);
    }

    public function testDelete()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // ASsert that the BlogPost was actually stored in the database
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        // Delete request to delete the blog post with dummy parameters
        $this->delete("/posts/{$post->id}")->assertStatus(302)->assertSessionHas('status');

        // Assert that the original dummy blogpost is no longer in the database after deletion
        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());

        // Assert that the blogpost was deleted status is there
        $this->assertEquals(session('status'),'Blog post was deleted!');
    }
}
