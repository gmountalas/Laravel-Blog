<?php

namespace Tests;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Use the UserFactory to create a User Model instance 
    // and save it to the database
    protected function user() 
    {
        return User::factory()->create();
    }

    // Use the BlogPostFactory to create a BlogPost Model instance 
    // and save it to the database, with id of the above User 
    protected function blogPost()
    {
        return BlogPost::factory()->create([
            'user_id' => $this->user()->id
        ]);
    }
}
