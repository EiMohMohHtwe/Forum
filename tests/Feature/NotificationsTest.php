<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
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

    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
           'user_id' => auth()->id(),
             'body' => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id(),
              'body' => 'Some reply here'
         ]);
 
         $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();

        create(DatabaseNotification::class);

        $user = auth()->user();

        $this->assertCount(
            1,
            $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json()
        );
    }

    function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();

        create(DatabaseNotification::class);

        $user = auth()->user();

        tap(auth()->user(), function ($user){

            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;
    
            $this->delete("/profiles/{$user->name}/notifiactions/{$notificationId}");
    
            $this->assertCount(0, $user->fresh()->unreadNotifications);

        });

    }
}
