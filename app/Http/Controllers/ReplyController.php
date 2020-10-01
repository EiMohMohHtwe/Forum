<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Reply;
use App\Inspections\Spam;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function store($ChannelId, Thread $thread, Spam $spam)
    {
        $spam->detect(request('body'));
        
        $thread->addReply([
           'body' => request('body'),
           'user_id' => auth()->id()
        ]);

       return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->delete();

        return back();
    }
}
