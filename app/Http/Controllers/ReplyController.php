<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Reply;
use App\Inspections\Spam;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function store($ChannelId, Thread $thread, Spam $spam)
    {
        if (Gate::denies('create', new Reply)) {
            return response(
                'You are posting too frequently. Please take a break. :)', 429
            );
        }
        $spam->detect(request('body'));

        if($thread->locked) {
            return response('Thread is locked', 422);
        }
        
        return $thread->addReply([
           'body' => request('body'),
           'user_id' => auth()->id()
        ])->load('owner');

      // return back();
    }

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $spam->detect(request('body'));
        
        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->delete();

        return back();
    }
}
