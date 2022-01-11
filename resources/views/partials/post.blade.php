<div style="{{ $style }}" class="post" id="post_{{$post->id}}">
    @include('partials.user_content', ['content' => $post->content])
    @if($post->hasImages())
        <div class="post_image_slideshow">
            @foreach($post->images() as $image)
                <img src="{{ url($image->path) }}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
            @endforeach
        </div>
    @endif
    @if(Auth::check())
        @if($post->content->inGroup() && \Illuminate\Support\Facades\Gate::allows('createIn-group', $post->content->group))
            @include('partials.reply_box', ['parent' => $post->content])
        @elseif(!$post->content->inGroup())
            @include('partials.reply_box', ['parent' => $post->content])
        @endif
    @endif
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
