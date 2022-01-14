<div class="group_search_result" id="group_search_result_{{$group->id}}">
    @if($group->cover_pic != null)
        <div class="image-cropper">
            <img class="img-circle" src="{{url($group->coverPic->path)}}" alt="Group Picture" height="54">
        </div>
    @endif
    <a href="groups/{{$group->id}}" class="text_result">{{$group->name}}</a>
    <hr/>
</div>
