@foreach($friendReqNots as $requestNot)
    <a href="/users/{{$requestNot->sender->id}}"><div>{{$requestNot->sender->name}} wants to be your friend!</div></a>
    <button class="btn btn-primary accept_friend" style="background-color: green" type="button" target_id="{{$requestNot->target->id}}" sender_id="{{$requestNot->sender->id}}" req_not_id="{{$requestNot->id}}">Accept</button>
    <button class="btn btn-primary deny_friend" style="background-color: red" type="button" target_id="{{$requestNot->target->id}}" sender_id="{{$requestNot->sender->id}}" req_not_id="{{$requestNot->id}}">Deny</button>
@endforeach
