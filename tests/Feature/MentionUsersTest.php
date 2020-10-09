<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User',['name' => 'JessicaAlba']);

        $this->signIn($john);

        $jane = create('App\User',['name' => 'EmmaStone']);
        
        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@JessicaAlba look at this.Also @EmmaStone'
            ]);
        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}
