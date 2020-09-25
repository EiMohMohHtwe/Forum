@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            @forelse ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h4 class="flex">
                                <a href="/threads/{{ $thread->id }}">
                                    {{ $thread->title }}
                                </a>
                            </h4>
                                <a href="{{ $thread->path() }}"> {{ $thread->replies_count }} replies </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <p>There are no relevant results at this time.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection