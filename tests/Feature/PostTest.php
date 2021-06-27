<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
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

    private function createDummyBlogPost($userId = null): BlogPost
    {
        // Create BlogPost with dummy data
        // Replace the following with Model Factory from BlogPostFactory
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Blog post content';
        // $post->save();
        // return $post;

        return BlogPost::factory()->newTitle()->create([
            'user_id' => $userId ?? $this->user()->id,
        ]);
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

    public function testSee1BlogPostWithComments() {
        // Arrange
        $post = $this->createDummyBlogPost();
        Comment::factory(4)->create([
            'blog_post_id' => $post->id
        ]);

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('4 comments');

    }

    // 
    public function testSeeBlogPostContentThatHasComments() {
        // Arrange
        $post = $this->createDummyBlogPost();
        $comment = Comment::factory()->create([
            'blog_post_id' => $post->id
        ]);

        // Act
        $response = $this->get('/posts/' . $post->id);

        // Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('Blog post content');
        $response->assertSeeText('Comments');
        $response->assertSeeText($comment->content);
        $response->assertSeeText('added');
    }

    public function testStoreValid()
    {
        // // Create a User
        // $user = $this->user();

        // // Authenticate as the user 
        // $this->actingAs($user);

        $parameters = [
            'title' => 'Valid title',
            'content' => "More than 10 characters"
        ];
        // Replace the above Create a user and Authenticate as the user
        // with actingAs($this->user())
        $this->actingAs($this->user())
            ->post('/posts', $parameters)
            ->assertStatus(302)
            ->assertSessionHas('status');

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

        $this->actingAs($this->user())
            ->post('/posts', $parameters)
            ->assertStatus(302)
            ->assertSessionHas('errors');
        
        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        // Arrange
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        // Assert - Verify the blogpost exists inside the database
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        // The parameters to modify the blogpost with
        $parameters = [
            'title' => 'A new named title',
            'content' => 'Content that is valid'
        ];

        // Put request to modify the blog post with dummy parameters
        $this->actingAs($user)
            ->put("/posts/{$post->id}", $parameters)
            ->assertStatus(302)
            ->assertSessionHas('status');

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
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        // ASsert that the BlogPost was actually stored in the database
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        // Delete request to delete the blog post with dummy parameters
        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        // Assert that the original dummy blogpost is no longer in the database after deletion
        // $this->assertDatabaseMissing('blog_posts', $post->getAttributes());
        $this->assertSoftDeleted('blog_posts', $post->getAttributes());

        // Assert that the blogpost was deleted status is there
        $this->assertEquals(session('status'),'Blog post was deleted!');
    }
}
