<p>Likes</p>
@foreach($likeNotifications as $likeNotification)
    <div>{{$likeNotification->sender->name}} liked <a href="/user_content/{{$likeNotification->content->id}}">this</a></div>
@endforeach
<p>Comments</p>
@foreach($commentNotifications as $commentNotification)
    <div>{{$commentNotification->comment->content->creator->name}} commented <a href="/user_content/{{$commentNotification->comment->content->id}}">here</a> </div>
@endforeach
<p>Shares</p>
@foreach($shareNotifications as $shareNotification)
    {{$shareNotification->share->content->creator->name}}
@endforeach
<p>Tags</p>
@foreach($tagNotifications as $tagNotification)
    {{$tagNotification->content->creator->name}} tagged you <a href="/user_content/{{$tagNotification->content->id}}">here</a>
@endforeach
