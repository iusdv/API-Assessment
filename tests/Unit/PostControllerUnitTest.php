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

    public function testGetPosts()
    {

        Post::factory()->count(3)->create();


        $response = $this->get('/api/posts');


        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []]);

        $this->assertCount(3, $response->json('data'));
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
            ->assertJsonStructure([
                'data' => [
                        'id',
                        'title',
                        'content',
                        'author',
                        'created_at',
                        'updated_at',
                ],
            ]);


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
             ->assertJsonStructure([
                'data' => [
                        'id',
                        'title',
                        'content',
                        'author',
                        'published_at',
                        'created_at',
                        'updated_at',
                ],
            ]);

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

    public function test_nonexisting_route_404()
    {
        $response = $this->get('/nonexistent-route');

        $response->assertStatus(404);
    }


    public function test_existing_route_200()
    {
        $response = $this->get('/api/home');
        $response->assertStatus(200);
    }

    public function test_nonexisting_post_id_200()
    {
        $response = $this->get('/api/home');
        $response->assertStatus(200);
    }

    public function test_nonexisting_post_message()
    {
        $nonExistentPostId = -1;

        $response = $this->get("/api/posts/{$nonExistentPostId}");

        $response->assertStatus(404)
            ->assertJson(['message' => 'This post does not exist.']);
    }


    //testing bonus filter search

    public function testFilterPostsByAuthor()
    {
        // test posts with different authors
        Post::factory()->create(['author' => 'test author 1 ']);
        Post::factory()->create(['author' => 'test author 2']);
        Post::factory()->create(['author' => 'Admin']);


        $response = $this->get('/api/posts?author=admin');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'author',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }
}
