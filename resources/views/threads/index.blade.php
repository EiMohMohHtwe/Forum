@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            @forelse ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <div class = "flex">
                                <h4>
                                    <a href="/threads/{{ $thread->id }}">
                                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong>
                                                {{ $thread->title }}
                                            </strong>
                                        @else
                                            {{ $thread->title }}
                                        @endif
                                    </a>
                                </h4>

                                <h6>Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }} </a></h6>
                            </div>

                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} replies
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="panel-footer">
                            {{ $thread->visits()->count() }} Visits
                        </div>
                    </div>

                </div>
            @empty
                <p>There are no relevant results at this time.</p>
            @endforelse
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Trending Threads</h4>
                </div>

                <div class="panel-body">
                    <ul class="list-group">
                        @foreach ($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{ url($thread->path) }}"> {{ $thread->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="form-group">
                    <form method="GET" action="/threads/search">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." name="q" class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-default" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
