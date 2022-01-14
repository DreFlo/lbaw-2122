<div class="user_search_result" id="user_search_result_{{$user->id}}">
    @if($user->profile_pic != null)
        <div class="image-cropper">
            <img class="img-circle" src="{{url($user->profilePic->path)}}" alt="Profile Picture" height="54">
        </div>
    @endif
    <a href="users/{{$user->id}}" class="text_result">{{$user->name}}</a>
    <hr/>
</div>
