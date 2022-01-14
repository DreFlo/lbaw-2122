<div class="row g-0 position-relative">
    <div class="comment_search_result" id="comment_search_result{{$comment->id}}" style="display: block">
        <h2 class="user_content_header" id="user_content_title_{{$comment->content->id}}">
            <div class="user_content_title">
                <a class="link" href="/users/{{$comment->content->creator_id}}">{{$comment->content->creator->name}}</a>
                @if($comment->content->inGroup())
                    in
                    <a class="link" href="/groups/{{$comment->content->group_id}}">{{$comment->content->group->name}}</a>
                @endif
                at {{date('g:i a d/m/y',strtotime($comment->content->timestamp))}}
                @if($comment->content->edited)
                    edited
                @endif
                @if($comment->content->priv_stat === 'Anonymous')
                    deleted
                @elseif($comment->content->priv_stat === 'Banned')
                    banned
                @endif
            </div>
            <div class="user_content_interaction_block like" user_id="{{auth()->user()->id}}" content_id="{{$comment->content->id}}" liked="{{$comment->content->likedByUser(auth()->user()->id)}}">
                <div style="flex: auto; justify-self: center">
                    @if(!$comment->content->likedByUser(auth()->user()->id))
                        <img src="{{asset('storage/graphics/empty_heart.png')}}" alt="Like" style="margin: 0">
                    @else
                        <img src="{{asset('storage/graphics/full_heart.png')}}" alt="Like" style="margin: 0">
                    @endif
                </div>
                <div style="flex: auto;">{{$comment->content->likeCount()}}</div>
            </div>
        </h2>
        <div class="user_content_text" id="user_content_text_{{$comment->content->id}}">
            {{$comment->content->text}}
        </div>
        <div class="user_content_text" id="user_content_tags_{{$comment->content->id}}">
            @if($comment->content->hasTags())
                <h5>Tagged</h5>
                @foreach($comment->content->tagged as $tag)
                    <a href="/users/{{$tag->id}}">{{$tag->name}}</a>
                @endforeach
            @endif
        </div>
        <a href="/comments/{{$comment->id}}" class="stretched-link"></a>
        <hr/>
    </div>
</div>
