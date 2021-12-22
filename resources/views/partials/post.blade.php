<div class="post" id="post_{{$post->id}}">
    <h2 class="post_title">
        <a class="link" href="/users/{{$post->content->creator_id}}">{{$post->content->creator->name}}</a>
        @if($post->content->inGroup())
            in
            <a class="link" href="/groups/{{$post->content->group_id}}">{{$post->content->group->name}}</a>
        @endif
        at {{$post->content->timestamp}}
        @if($post->edited)
            edited
        @endif
    </h2>
    <div class="post_content">
        {{$post->content->text}}
    </div>
    @if($post->content->hasComments())
    <div class="post_comments">
        <div class="post_comments_banner">Comments</div>
        @foreach($post->content->comments as $comment)
            @include('partials.comment', ['comment', $comment])
        @endforeach
    </div>
    @endif
</div>
