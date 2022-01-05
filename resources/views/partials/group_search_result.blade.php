<div class="group_search_result" id="group_search_result_{{$group->id}}">
    @if($group->cover_pic != null)
        <img class="rounded-circle m-2" src="{{url($group->coverPic->path)}}" alt="Cover Picture" height="54">
    @endif
    @if($group->priv_stat == 'Public')
        <a href="groups/{{$group->id}}" class="text-dark">{{$group->name}}</a>
    @elseif($group->priv_stat == 'Private')
        @if($group->belonging)
            <a href="groups/{{$group->id}}" class="text-dark">{{$group->name}}</a>
            <button type="button" class="btn btn-lg btn-danger m-2" disabled>Joined</button>
        @else
            <a class="text-dark">{{$group->name}}</a>
            <a href="#" class="btn btn-danger btn-lg active m-2" role="button" aria-pressed="true">Request</a>
        @endif
    @endif
    <hr/>
</div>
