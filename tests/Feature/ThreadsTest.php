<?php

namespace Tests\Feature;

use App\Visits;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    /**
     * A basic test example.
     *
     * @return void
     */

     /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = factory('App\Models\Thread')->create();

        $this->get('/threads')

            ->assertSee($thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread()
    {
        $thread = factory('App\Models\Thread')->create();

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $thread = factory('App\Models\Thread')->create();

        $reply = create('App\Models\Reply', ['thread_id' => $thread->id]);

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_thread_has_a_path()
    {
        $thread = create('App\Models\Thread');

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $thread = factory('App\Models\Thread')->create();

        $this->assertInstanceOf('App\Models\User', $thread->creator);
    }

    /** @test */
    function a_thread_has_a_replies()
    {
        $thread = create('App\Models\Thread');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    /** @test */
    public function a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Models\Thread', ['body' => '<p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $thread->body);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Models\Thread');

        $this->assertInstanceOf('App\Models\Channel', $thread->channel);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Models\Channel');
        $threadInChannel = create('App\Models\Thread', ['channel_id' => $channel->id]);

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\Models\User', ['name' => 'test2']));

        $threadByJohn = create('App\Models\Thread', ['user_id' => auth()->id()]);

        $this->get('threads?by=test2')
            ->assertSee($threadByJohn->title);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Models\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Models\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Models\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    function a_thread_records_each_visit()
    {
        $thread = make('App\Models\Thread', ['id' => 1]);

        Redis::del("threads.{$thread->id}.visits");

        $thread->recordVisit();

        $this->assertEquals(1, $thread->visits());

        $thread->recordVisit();

        $this->assertEquals(2, $thread->visits());
    }

    /** @test */
    function a_thread_may_be_locked()
    {
        $thread = create('App\Models\Thread');

        $this->assertFalse($thread->locked);

        $thread->lock();

        $this->assertTrue($thread->locked);
    }
}
