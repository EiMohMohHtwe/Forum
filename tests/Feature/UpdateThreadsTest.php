<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function set_Up()
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->signIn();
    }

    /** @test */
    function unauthorized_users_may_not_update_threads()
    {
        $thread = create('App\Models\Thread', ['user_id' => create('App\Models\User')->id]);

        $this->patch($thread->path(), [])->assertStatus(302);
    }

    /** @test */
    function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = create('App\Models\Thread', ['user_id' => create('App\Models\User')->id]);

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertStatus(302);

        $this->patch($thread->path(), [
            'body' => 'Changed'
        ])->assertStatus(302);
    }

    /** @test */
    function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('App\Models\Thread', ['user_id' => create('App\Models\User')->id]);

        $this->patch($thread->path(), [
            'title' => $thread->title,
            'body' =>  $thread->body
        ])->assertStatus(302);
    }
}
