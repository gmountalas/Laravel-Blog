<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        $response->assertSeeText('Hello John');
        $response->assertSeeText('Welcome to Laravel!');

    }

    public function testContactPageIsWorkingCorrectly() 
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact page');
        $response->assertSeeText('Hello this is contact!');

    }

}
