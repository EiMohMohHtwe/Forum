<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function set_Up()
    {
        parent::setUp();

        Redis::del('trending_threads');

        $this->assertEmpty(Redis::zrevrange('trending_threads', 0,-1));
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0,-1));

        $thread = create('App\Models\Thread');

        $this->call('GET', $thread->path());

        $this->assertCount(1, Redis::zrevrange('trending_threads', 0,-1));

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, json_decode($trending[0])->title);
    }

}