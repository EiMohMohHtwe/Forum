<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\Models\User',['name' => 'JessicaAlba']);

        $this->signIn($john);

        $jane = create('App\Models\User',['name' => 'EmmaStone']);
        
        $thread = create('App\Models\Thread');

        $reply = make('App\Models\Reply', [
            'body' => '@JessicaAlba look at this.Also @EmmaStone'
            ]);
        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(0, $jane->notifications);
    }
}
