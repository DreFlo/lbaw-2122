<div style="{{ $style }}" class="post" id="post_{{$post->id}}">
    @include('partials.user_content', ['content' => $post->content])
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
            @if(\Illuminate\Support\Facades\Gate::allows('view-content', $comment->content))
                @include('partials.comment', ['comment' => $comment])
            @endif
        @endforeach
    </div>
    @endif
</div>
