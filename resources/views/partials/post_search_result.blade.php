<div class="row g-0 position-relative">
    <div class="post_search_result" id="post_search_result_{{$post->id}}" style="display: block">
        <h2 class="user_content_header" id="user_content_title_{{$post->content->id}}">
            <div class="user_content_title">
                <a class="link" href="/users/{{$post->content->creator_id}}">{{$post->content->creator->name}}</a>
                @if($post->content->inGroup())
                    in
                    <a class="link" href="/groups/{{$post->content->group_id}}">{{$post->content->group->name}}</a>
                @endif
                at {{date('g:i a d/m/y',strtotime($post->content->timestamp))}}
                @if($post->content->edited)
                    edited
                @endif
                @if($post->content->priv_stat === 'Anonymous')
                    deleted
                @elseif($post->content->priv_stat === 'Banned')
                    banned
                @endif
            </div>
                <div class="user_content_interaction_block like" user_id="{{auth()->user()->id}}" content_id="{{$post->content->id}}" liked="{{$post->content->likedByUser(auth()->user()->id)}}">
                    <div style="flex: auto; justify-self: center">
                        @if(!$post->content->likedByUser(auth()->user()->id))
                            <img src="{{asset('storage/graphics/empty_heart.png')}}" alt="Like" style="margin: 0">
                        @else
                            <img src="{{asset('storage/graphics/full_heart.png')}}" alt="Like" style="margin: 0">
                        @endif
                    </div>
                    <div style="flex: auto;">{{$post->content->likeCount()}}</div>
                </div>
        </h2>
        <div class="user_content_text" id="user_content_text_{{$post->content->id}}">
            {{$post->content->text}}
        </div>
        <div class="user_content_text" id="user_content_tags_{{$post->content->id}}">
            @if($post->content->hasTags())
                <h5>Tagged</h5>
                @foreach($post->content->tagged as $tag)
                    <a href="/users/{{$tag->id}}">{{$tag->name}}</a>
                @endforeach
            @endif
        </div>

        @if($post->hasImages())
            <div class="post_image_slideshow">
                @foreach($post->images() as $image)
                    <img src="{{ url($image->path) }}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
                @endforeach
            </div>
        @endif
        <a href="/posts/{{$post->id}}" class="stretched-link"></a>
        <hr/>
    </div>
</div>
