<div class="comment" id="comment_{{$comment->id}}">
    @include('partials.user_content', ['content' => $comment->content])
    @include('partials.reply_box', ['parent_id' => $comment->id])
    @if($comment->content->hasComments())
    <div class="comment_replies" id="comment_replies_{{$comment->id}}">
        @foreach($comment->content->sortedComments() as $comment)
            @include('partials.comment', ['comment', $comment])
        @endforeach
    </div>
    @endif
</div>
