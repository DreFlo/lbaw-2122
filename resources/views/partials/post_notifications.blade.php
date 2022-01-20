<p><b>Likes</b></p>
@foreach($likeNotifications as $likeNotification)
<a href="/user_content/{{$likeNotification->content->id}}">{{$likeNotification->sender->name}} liked your content!</a>
@endforeach
<p><b>Comments</b></p>
@foreach($commentNotifications as $commentNotification)
<a href="/user_content/{{$commentNotification->comment->content->id}}">{{$commentNotification->comment->content->creator->name}} commented on your content!</a>
@endforeach
<p><b>Shares</b></p>
@foreach($shareNotifications as $shareNotification)
    {{$shareNotification->share->content->creator->name}}
@endforeach
<p><b>Tags</b></p>
@foreach($tagNotifications as $tagNotification)
<a href="/user_content/{{$tagNotification->content->id}}">{{$tagNotification->content->creator->name}} tagged you!</a>
@endforeach
