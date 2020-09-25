<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function store(Channel $channel,Thread $thread)
    {
        $thread->addReply([
           'body' => request('body'),
           'user_id' => auth()->id()
        ]);

       return back();
    }
}
