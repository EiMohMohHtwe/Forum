<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }} " class="panel" :class="isBest ? 'panel-success': 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                </h5>
            
                <div>
                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                            {{ $reply->favorites()->count() }} Favorites
                        </button></br>
                    </form>
                </div>
            </div>
        </div>


        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-html="body"></div>
        </div></br>

        @can ('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <form method="POST" action="/replies/{{ $reply->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form>
               <button class="btn btn-default" @click="markBestReply" v-show="! isBest">Best Reply?</button>
            </div>
        @endcan
    </div>
</reply>