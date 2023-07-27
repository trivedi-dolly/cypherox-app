<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CypheroxTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSignup()
    {
        $response = $this->post('/signup',[
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'test@1234'
        ]);

        $response->assertStatus(200);
    }
    function testLogin() {
        $response = $this->post('/login',[
            'email' => 'test@example.com',
            'password' => 'test@1234'
        ]);
        $response->assertStatus(200);
    }
    function testCreatePost() {
        $response = $this->post('/post',[
            'name' => 'Hello World',
            'user_id' => 2
        ]);
        $response->assertStatus(200);
    }
    function testCreateComment() {
        $response = $this->post('/comment',[
            'comments'=>'Nice',
            'user_id' =>2
        ]);
        $response->assertStatus(200);
    }
    function testLikePostComment() {
        $response = $this->post('/like-post-comments',[
            'status' => 200,
            'message' => 'Post/Comment Liked Successfully'
        ]);
        $response->assertStatus(200);
    }
    function testGetPostsWithLikeComments() {
        $response = $this->get('/posts/2',[
            'name' => 'hello world',
            'user_id' =>2,
            'comments' => [
                'comments' => 'nice',
                'user_id' =>1,
                'total_likes' =>1,
                'likes'=>[]
            ]
        ]);
        $response->assertStatus(200);
    }
    function testunlikePostOrComment()  {
        $response = $this->post('/unlike-post-comments',[
            'status' => 200,
            'message' => 'Post/Comment unlike successfully'
        ]);
        $response->assertStatus(200);
    }
}
