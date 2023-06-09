<h2 class="user_content_header" id="user_content_title_{{$content->id}}">
    <div class="user_content_title">
        <a class="link" href="/users/{{$content->creator_id}}">{{$content->creator->name}}</a>
        @if($content->inGroup())
            in
            <a class="link" href="/groups/{{$content->group_id}}">{{$content->group->name}}</a>
        @endif
        at {{date('g:i a d/m/y',strtotime($content->timestamp))}}
        @if($content->edited)
            edited
        @endif
        @if($content->priv_stat === 'Anonymous')
            deleted
        @elseif($content->priv_stat === 'Banned')
            banned
        @endif
    </div>
    @if(Auth::check())
        @if(auth()->user()->id === $content->creator_id || auth()->user()->isAdmin() || ($content->inGroup() && $content->group->isModerator(auth()->user())))
            <div class="user_content_interaction_block" style="flex: auto; justify-self: center">
                <form action="{{route('user_content.destroy', $content)}}" method="POST" class="user_content_control_form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Delete"><img src="{{asset('storage/graphics/delete.png')}}" alt="Delete"></button>
                </form>
            </div>
        @endif
        @if(auth()->user()->id === $content->creator_id)
            <div class="user_content_interaction_block" style="flex: auto; justify-self: center">
                <button title="Edit">
                    <a href="{{route('user_content.edit', $content->id)}}">
                        <img src="{{asset('storage/graphics/edit.png')}}" alt="Edit">
                    </a>
                </button>
            </div>
        @endif
        @if($content->isPost() && $content->priv_stat === 'Public')
            <div class="user_content_interaction_block" style="flex: auto; justify-self: center">
                <button title="Share">
                    <a href="{{route('posts.share', \App\Models\Post::find($content->id))}}">
                        <img src="{{asset('storage/graphics/share.png')}}" alt="share">
                    </a>
                </button>
            </div>
        @endif
        <div class="user_content_interaction_block like" user_id="{{auth()->user()->id}}" content_id="{{$content->id}}" liked="{{$content->likedByUser(auth()->user()->id)}}">
            <div style="flex: auto; justify-self: center">
                @if(!$content->likedByUser(auth()->user()->id))
                    <img src="{{asset('storage/graphics/empty_heart.png')}}" alt="Like" style="margin: 0">
                @else
                    <img src="{{asset('storage/graphics/full_heart.png')}}" alt="Unlike" style="margin: 0">
                @endif
            </div>
            <div style="flex: auto;">{{$content->likeCount()}}</div>
        </div>
    @endif
</h2>
<div class="user_content_text" id="user_content_text_{{$content->id}}">
    {{$content->text}}
</div>
<div class="user_content_text" id="user_content_tags_{{$content->id}}">
    @if($content->hasTags())
        <h5>Tagged</h5>
        @foreach($content->tagged as $tag)
            <a href="/users/{{$tag->id}}">{{$tag->name}}</a>
        @endforeach
    @endif
</div>
