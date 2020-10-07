@extends('layouts.app')

@section('content')
<thread-view :initialRepliesCount=" {{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> 
                        <div class="level">
                            <span class="flex">
                                <h4><a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:{{ $thread->title }}</h4>
                            </span>
                            @can ('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- <form method="POST" action=""> -->
                            @csrf
                            <div class="form-group row">
                                <h5>{{ $thread->body }}</h5></br>
                            </div>
                            <div class="form-group row">
                                @foreach ($thread->replies as $reply)
                                    @include('threads.reply')
                                @endforeach
                            </div>
                        <!-- </form> -->
                        @if( auth()->check())
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <form method="POST" action=" {{ $thread->path() . '/replies' }}">
                                    @csrf
                                    <div class="form_group">
                                        <textarea name='body' id='body' class="form_control" placeholder="Have something to say?"  rows="4" cols="78"></textarea>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-0"></br>
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Post') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                            <p class="text-center">Please <a href="{{ route('login') }}"> sign in </a> to participate in this discussion.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, and currently
                            has {{ $thread->replies_count }} comments.
                        </p>
                        <p>
                            <subscribe-button></subscribe-button>

                            <button class="btn btn-primary" @click="locked = true">Lock</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection

