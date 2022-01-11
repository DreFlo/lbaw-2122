<div class="comment" id="comment_{{$comment->id}}">
    @include('partials.user_content', ['content' => $comment->content])
    @if(Auth::check())
        @if($comment->content->inGroup() && \Illuminate\Support\Facades\Gate::allows('createIn-group', $comment->content->group))
            @include('partials.reply_box', ['parent' => $comment->content])
        @elseif(!$comment->content->inGroup())
            @include('partials.reply_box', ['parent' => $comment->content])
        @endif
    @endif
    @if($comment->content->hasComments())
    <div class="comment_replies" id="comment_replies_{{$comment->id}}">
        @foreach($comment->content->sortedComments() as $comment)
            @if(\Illuminate\Support\Facades\Gate::allows('view-content', $comment->content))
                @include('partials.comment', ['comment', $comment])
            @endif
        @endforeach
    </div>
    @endif
</div>
