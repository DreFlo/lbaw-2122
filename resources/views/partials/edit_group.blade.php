
@if($group->isModerator(auth()->user()) || auth()->user()->isAdmin)
<div class="delete_group">
    <form action="/groups/{{$group->id}}" method="POST" class="user_content_control_form">
        @csrf
        @method('DELETE')
        <button type="submit" title="Delete">Delete Group</button>
    </form>
</div>
@endif