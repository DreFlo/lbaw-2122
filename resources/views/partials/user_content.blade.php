<h2 class="user_content_header" id="user_content_title_{{$content->id}}">
    <div class="user_content_title">
        <a class="link" href="/users/{{$content->creator_id}}">{{$content->creator->name}}</a>
        @if($content->inGroup())
            in
            <a class="link" href="/groups/{{$content->group_id}}">{{$content->group->name}}</a>
        @endif
        at {{$content->timestamp}}
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
        <div class="user_content_interaction_block">
            @if(!$content->likedByUser(auth()->user()->id))
                <form action="{{route('likes.add')}}" method="POST" class="user_content_control_form">
                    @csrf
                    @method('POST')
                    <input type="hidden" value="{{$content->id}}" name="content_id">
                    <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                    <button type="submit" title="Like"><img src="{{asset('storage/graphics/empty_heart.png')}}" alt="Like"></button>
                </form>
            @else
                <form action="{{route('likes.remove')}}" method="POST" class="user_content_control_form">
                    @csrf
                    @method('POST')
                    <input type="hidden" value="{{$content->id}}" name="content_id">
                    <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                    <button type="submit" title="Unlike"><img src="{{asset('storage/graphics/full_heart.png')}}" alt="Unlike"></button>
                </form>
            @endif
            <div style="flex: auto; justify-content: center">{{$content->likeCount()}}</div>
        </div>
    @endif
</h2>
<div class="user_content_text" id="user_content_text_{{$content->id}}">
    {{$content->text}}
</div>
