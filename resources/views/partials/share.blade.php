<div class="post" id="share_{{$share->id}}">
    <h2 class="post_header" id="share_title_{{$share->id}}">
        <div class="post_title">
            <a class="link" href="/users/{{$share->content->creator_id}}">{{$share->content->creator->name}}</a>
            @if($share->content->inGroup())
                in
                <a class="link" href="/groups/{{$share->content->group_id}}">{{$share->content->group->name}}</a>
            @endif
            at {{$share->content->timestamp}}
            @if($share->content->edited)
                edited
            @endif
        </div>
    </h2>
    <div class="post_content" id="share_content_{{$share->id}}">
        {{$share->content->text}}
    </div>
    <div class="post" id="post_{{$share->post->id}}" style="width: 98%">
        <h2 class="post_header" id="post_title_{{$share->post->id}}">
            <div class="post_title">
                <a class="link" href="/users/{{$share->post->content->creator_id}}">{{$share->post->content->creator->name}}</a>
                @if($share->post->content->inGroup())
                    in
                    <a class="link" href="/groups/{{$share->post->content->group_id}}">{{$share->post->content->group->name}}</a>
                @endif
                at {{$share->post->content->timestamp}}
                @if($share->post->content->edited)
                    edited
                @endif
                <a href="/posts/{{$share->post->id}}">Post</a>
            </div>
        </h2>
        <div class="post_content" id="post_content_{{$share->post->id}}">
            {{$share->post->content->text}}
        </div>
        @if($share->post->hasImages())
            <div class="post_image_slideshow">
                @foreach($share->post->images() as $image)
                    <img src="{{asset($image->path)}}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
                @endforeach
                @if(count($share->post->images()) > 1)
                    <a class="post_prev_image" onclick="plusPostSlides(-1)">&#10094;</a>
                    <a class="post_next_image" onclick="plusPostSlides(1)">&#10095;</a>
                @endif
            </div>
            <div style="text-align:center; margin-top: 5px">
                @if(count($share->post->images()) > 1)
                    @for($i = 1; $i <= count($share->post->images()); $i++)
                        <span class="dot" onclick="currentPostSlide({{$i}})"></span>
                    @endfor
                @endif
            </div>
        @endif
    </div>
    @include('partials.reply_box', ['parent_id' => $share->id])
    @if($share->content->hasComments())
        <div class="post_comments" id="share_comments_{{$share->id}}">
            <div class="post_comments_banner">Comments</div>
            @foreach($share->content->sortedComments() as $comment)
                @include('partials.comment', ['comment', $comment])
            @endforeach
        </div>
    @endif
</div>
