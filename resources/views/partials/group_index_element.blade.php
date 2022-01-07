<div class="group_search_result" id="group_search_result_{{$group->id}}">
    @if($group->cover_pic != null)
        <img class="rounded-circle m-2" src="{{url($group->coverPic->path)}}" alt="Cover Picture" height="54">
    @endif
    <a href="/groups/{{$group->id}}" class="text-dark">{{$group->name}}</a>
    @if($group->priv_stat === 'Anonymous')
        deleted
    @endif
    @if(auth()->user()->isAdmin())
        <div class="user_content_interaction_block" style="flex: auto; justify-self: center">
            <form action="{{route('groups.destroy', $group)}}" method="POST" class="user_content_control_form">
                @csrf
                @method('DELETE')
                <button type="submit" title="Delete"><img src="{{asset('storage/graphics/delete.png')}}" alt="Delete"></button>
            </form>
        </div>
    @endif
    <hr/>
</div>
