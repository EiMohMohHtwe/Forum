<?php

namespace App\Models;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;
use Illuminate\Support\Facades\Redis;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });

        static::deleting(function ($thread){
            $thread->replies()->delete();
        });
    }

    public function path()
    {
        //return '/threads' . $this->channel->slug . '/' . $this->id;
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        //$this->subscriptions
        //    ->filter(function($sub) use ($reply){
        //        return $sub->user_id != $reply->user_id;
        //    })
            
        //    ->each->notify($reply);
            
        return $reply;
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function recordVisit()
    {
        Redis::incr("threads.{$this->id}.visits");

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey());
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    protected function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}
