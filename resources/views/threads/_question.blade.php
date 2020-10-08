<div class="card" v-if="editing">
    <div class="card-header"> 
        <div class="level">

            <input type="text" vlaue="{{ $thread->title }}">

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
        <div class="form-group row">
            <textarea class="form-control" rows="5" v-model="form.body">{{ $thread->body }}</textarea>
        </div>

        <div class="form-group row">
            <button class="btn btn-xs" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-xs" @click="update">Update</button>
            <button class="btn btn-xs" @click="editing = false">Cancel</button>
        </div>
    </div>
</div>

<div class="card" v-else>
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
        <div class="form-group row">
            <h5>{{ $thread->body }}</h5>
        </div>

        <div class="form-group row">
            <button class="btn btn-xs" @click="editing = true">Edit</button>
        </div>
    </div>
</div>