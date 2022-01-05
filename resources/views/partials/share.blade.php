<div class="post" id="share_{{$share->id}}">
    @include('partials.user_content', ['content' => $share->content])
    <div class="post" id="post_{{$share->post->id}}" style="width: 98%">
        @include('partials.user_content', ['content' => $share->post->content])
        @if($share->post->hasImages())
            <div class="post_image_slideshow">
                @foreach($share->post->images() as $image)
                    <img src="{{asset($image->path)}}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
                @endforeach
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
