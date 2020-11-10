<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function non_administrators_may_not_lock_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Models\Thread', ['user_id' => create('App\Models\User')->id]);

        $this->post('locked-threads.store', $thread->toArray())->assertStatus(404);

        $this->assertFalse(! ! $thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_threads()
    {
        $this->signIn(factory('App\Models\User')->states('administrator')->create());

        $thread = create('App\Models\Thread', ['user_id' => create('App\Models\User')->id]);

        $this->post('locked-threads.store', $thread->toArray())->assertStatus(404);

        $this->assertFalse(! ! $thread->fresh()->locked, 'Failed asserting that the thread was locked.');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
