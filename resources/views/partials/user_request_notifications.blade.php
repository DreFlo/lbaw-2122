@foreach($friendReqNots as $requestNot)
    <div><a href="/users/{{$requestNot->sender->id}}">{{$requestNot->sender->name}}</a> wants to be your friend</div>
    <button class="btn btn-primary accept_friend" style="background-color: green" type="button" target_id="{{$requestNot->target->id}}" sender_id="{{$requestNot->sender->id}}" req_not_id="{{$requestNot->id}}">Accept</button>
    <button class="btn btn-primary deny_friend" style="background-color: red" type="button" target_id="{{$requestNot->target->id}}" sender_id="{{$requestNot->sender->id}}" req_not_id="{{$requestNot->id}}">Deny</button>
@endforeach
