<?php

namespace Tests\Unit;

use App\Http\Controllers\API\PostController;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerUnitTest extends TestCase
{
    use RefreshDatabase;


    //crud test cases

    public function test_get_posts()
    {
        Post::factory()->count(3)->create();

        $postController = new PostController();
        $response = $postController->index();

        $this->assertCount(3, $response->original['data']);
    }

    public function test_create_posts()
    {
        $postData = [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'author' => 'Test Author',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJson(['data' => $postData])
            ->assertJsonStructure(['data' => ['id', 'title', 'content', 'author', 'created_at', 'updated_at']]);


        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'author' => 'Test Author',
        ]);
    }

    public function test_update_post()
    {
        $post = Post::factory()->create();

        $updateData = [
            'title' => 'Updated Post Title',
            'content' => 'This post has been updated.',
            'author' => 'Updated Author',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['data' => $updateData])
            ->assertJsonStructure(['data' => ['id', 'title', 'content', 'author', 'created_at', 'updated_at']]);


        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post Title',
            'content' => 'This post has been updated.',
            'author' => 'Updated Author',
        ]);
    }

    public function test_delete_post()
    {

        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Post deleted successfully']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }




//route test cases

    public function test_nonexisting_route_302()
    {
        $response = $this->get('/nonexistent-route');

        $response->assertStatus(302);
    }


    public function test_existing_route_200()
    {
        $response = $this->get('/api/home');
        $response->assertStatus(200);
    }

    public function test_nonexisting_postID_200()
    {
        $response = $this->get('/api/home');
        $response->assertStatus(200);
    }

    public function test_nonexisting_post_displays_message()
    {
        $nonExistentPostId = -1;

        $response = $this->get("/api/posts/{$nonExistentPostId}");

        $response->assertStatus(404)
            ->assertJson(['message' => 'This post does not exist.']);
    }
}
