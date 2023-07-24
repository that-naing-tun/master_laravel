<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function testBlogPostWhenNothingInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No Blog Post Yet!');
    }

    public function testSee1BlogPostWhenThereIs1()
    {
        //Arrange
        $post = $this->createDummyBlogPost();

        //Act
        $response = $this->get('/posts');

        //Assert
        $response->assertSeeText('New Title');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title'
        ]);
    }

    public function testStoreValid()
    {
        $params = ['title' => 'Valid Title', 'content' => 'At least 10 character'];
        $this->post('/posts', $params)->assertStatus(302)->assertSessionHas('status');
        $this->assertEquals(session('status'), 'Blog Post was Created');
    }

    public function testStoreFail()
    {
        $params = ['title' => 'x', 'content' => 'x'];
        $this->post('/posts', $params)->assertStatus(302)->assertSessionHas('errors');
        $message = session('errors')->getMessages();
        $this->assertEquals($message['title'][0], 'The title field must be at least 10 characters.');
        $this->assertEquals($message['content'][0], 'The content field must be at least 10 characters.');
        dd($message->getMessages());
    }

    public function testUpdateValid()
    {
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = ['title' => 'A new named title', 'content' => 'Content was Changed'];
        $this->put("/posts/{$post->id}", $params)->assertStatus(302)->assertSessionHas('status');
        $this->assertEquals(session('status'), 'Blog Post was Updated');

        $this->assertDatabaseMissing('blog_posts', $post->toArray());

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title'
        ]);
    }

    public function testDelete()
    {
        $post = $this->createDummyBlogPost();
        $this->assertDatabaseHas('blog_posts', $post->toArray());
        $this->put("/posts/{$post->id}")->assertStatus(302)->assertSessionHas('status');
        $this->assertEquals(session('status'), 'Blog Post was Deleted');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    private function createDummyBlogPost()
    {
        $post = new BlogPost();
        $post->title = 'New Title';
        $post->content = 'Content of the blog post';
        $post->save();
        return $post;
    }
}
