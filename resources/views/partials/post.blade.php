<div class="post" id="post_{{$post->id}}">
    <h2 class="post_title" id="post_title_{{$post->id}}">
        <a class="link" href="/users/{{$post->content->creator_id}}">{{$post->content->creator->name}}</a>
        @if($post->content->inGroup())
            in
            <a class="link" href="/groups/{{$post->content->group_id}}">{{$post->content->group->name}}</a>
        @endif
        at {{$post->content->timestamp}}
        @if($post->content->edited)
            edited
        @endif
    </h2>
    <div class="post_content" id="post_content_{{$post->id}}">
        {{$post->content->text}}
    </div>
    <img src="{{asset('storage/images/'.$post->images()[0]->path)}}" class="post_image">
    <!-- TODO Insert Reply Function Here -->
    @if($post->content->hasComments())
    <div class="post_comments" id="post_comments_{{$post->id}}">
        <div class="post_comments_banner">Comments</div>
        @foreach($post->content->sortedComments() as $comment)
            @include('partials.comment', ['comment', $comment])
        @endforeach
    </div>
    @endif
</div>
