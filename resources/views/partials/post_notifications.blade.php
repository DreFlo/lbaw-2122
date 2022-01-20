<p>Likes</p>
@foreach($likeNotifications as $likeNotification)
<a href="/user_content/{{$likeNotification->content->id}}">{{$likeNotification->sender->name}} liked your content!</a>
@endforeach
<p>Comments</p>
@foreach($commentNotifications as $commentNotification)
<a href="/user_content/{{$commentNotification->comment->content->id}}">{{$commentNotification->comment->content->creator->name}} commented on your content!</a>
@endforeach
<p>Shares</p>
@foreach($shareNotifications as $shareNotification)
    {{$shareNotification->share->content->creator->name}}
@endforeach
<p>Tags</p>
@foreach($tagNotifications as $tagNotification)
<a href="/user_content/{{$tagNotification->content->id}}">{{$tagNotification->content->creator->name}} tagged you!</a>
@endforeach
