@foreach($groupInvNots as $inviteNot)
    <div><a href="/groups/{{$inviteNot->group->id}}">{{$inviteNot->group->name}}</a> wants you to be a member</div>
    <button class="btn btn-primary accept_invite" style="background-color: green" type="button" group_id="{{$inviteNot->group->id}}" user_id="{{$inviteNot->user->id}}" inv_not_id="{{$inviteNot->id}}">Accept</button>
    <button class="btn btn-primary deny_invite" style="background-color: red" type="button" group_id="{{$inviteNot->group->id}}" user_id="{{$inviteNot->user->id}}" inv_not_id="{{$inviteNot->id}}">Deny</button>
@endforeach
