<div class="post" id="post_{{$post->id}}">
    <h2 class="post_header" id="post_title_{{$post->id}}">
        <div class="post_title">
        <a class="link" href="/users/{{$post->content->creator_id}}">{{$post->content->creator->name}}</a>
        @if($post->content->inGroup())
            in
            <a class="link" href="/groups/{{$post->content->group_id}}">{{$post->content->group->name}}</a>
        @endif
        at {{$post->content->timestamp}}
        @if($post->content->edited)
            edited
        @endif
        </div>
        @if(Auth::check())
            @if(auth()->user()->id === $post->content->creator_id)
                <form action="{{route('posts.destroy', $post)}}" method="POST" class="post_control_form">
                    @csrf
                    @method('DELETE')
                    <button type="submit">D</button>
                </form>
            @endif
            <form action="{{route('posts.destroy', $post)}}" method="POST" class="post_control_form">
                @csrf
                @method('DELETE')
                <button type="submit">L</button>
            </form>
        @endif
    </h2>
    <div class="post_content" id="post_content_{{$post->id}}">
        {{$post->content->text}}
    </div>
    @if($post->hasImages())
        <div class="post_image_slideshow">
            @foreach($post->images() as $image)
                <img src="{{asset($image->path)}}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
            @endforeach
            @if(count($post->images()) > 1)
                <a class="post_prev_image" onclick="plusPostSlides(-1)">&#10094;</a>
                <a class="post_next_image" onclick="plusPostSlides(1)">&#10095;</a>
            @endif
        </div>
        <div style="text-align:center; margin-top: 5px">
            @if(count($post->images()) > 1)
                @for($i = 1; $i <= count($post->images()); $i++)
                    <span class="dot" onclick="currentPostSlide({{$i}})"></span>
                @endfor
            @endif
        </div>
    @endif
    @include('partials.reply_box', ['parent_id' => $post->id])
    @if($post->content->hasComments())
    <div class="post_comments" id="post_comments_{{$post->id}}">
        <div class="post_comments_banner">Comments</div>
        @foreach($post->content->sortedComments() as $comment)
            @include('partials.comment', ['comment', $comment])
        @endforeach
    </div>
    @endif
</div>
