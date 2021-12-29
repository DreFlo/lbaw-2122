<div class="comment" id="comment_{{$comment->id}}">
    <h3 class="comment_title" id="comment_title_{{$comment->id}}">
        <a class="link" href="/users/{{$comment->content->creator->id}}">
            {{$comment->content->creator->name}}
        </a>
        at {{$comment->content->timestamp}}
        @if($comment->content->edited)
            edited
        @endif
    </h3>
    <div class="comment_content" id="comment_content_{{$comment->id}}">
        {{$comment->content->text}}
    </div>
    <form action="{{route('comment.add')}}" method="POST" class="post_control_form">
        @csrf
        @method('POST')
        <div class="form-group">
            <input type="hidden" name="parent_id" value="{{$comment->id}}">
            <label>
                <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
            </label>
        </div>
        <button type="submit">Reply</button>
    </form>
    @if($comment->content->hasComments())
    <div class="comment_replies" id="comment_replies_{{$comment->id}}">
        @foreach($comment->content->sortedComments() as $comment)
            @include('partials.comment', ['comment', $comment])
        @endforeach
    </div>
    @endif
</div>
