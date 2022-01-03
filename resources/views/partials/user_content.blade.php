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
    </div>
    @if(Auth::check())
        @if(auth()->user()->id === $content->creator_id)
            <!-- TODO Add stuff for post owner -->
            <div class="user_content_interaction_block">
                <form action="{{route('user_content.destroy', $content)}}" method="POST" class="user_content_control_form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Delete"><img src="{{asset('storage/graphics/delete.png')}}" alt="Delete"></button>
                </form>
            </div>
            <div class="user_content_interaction_block">
                <button title="Edit">
                    <a href="{{route('user_content.edit', $content->id)}}">
                        <img src="{{asset('storage/graphics/edit.png')}}" alt="Edit">
                    </a>
                </button>
            </div>
        @endif
        <!-- TODO Add stuff for any user interaction, Like/Unlike may not be best practice -->
        @if($content->isPost() && $content->priv_stat === 'Public')
            <div class="user_content_interaction_block">
                <button title="Share">
                    <a href="{{route('posts.share', \App\Models\Post::find($content->id))}}">
                        <img src="{{asset('storage/graphics/share.png')}}" alt="share">
                    </a>
                </button>
            </div>
        @endif
        <div class="user_content_interaction_block like" user_id="{{auth()->user()->id}}" content_id="{{$content->id}}" liked="{{$content->likedByUser(auth()->user()->id)}}">
            @if(!$content->likedByUser(auth()->user()->id))
                <img src="{{asset('storage/graphics/empty_heart.png')}}" alt="Like">
            @else
                <img src="{{asset('storage/graphics/full_heart.png')}}" alt="Like">
            @endif
            <div style="flex: auto; justify-content: center">{{$content->likeCount()}}</div>
        </div>
    @endif
</h2>
<div class="user_content_text" id="user_content_text_{{$content->id}}">
    {{$content->text}}
</div>
