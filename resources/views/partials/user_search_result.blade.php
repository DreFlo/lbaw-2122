<div class="user_search_result" id="user_search_result_{{$user->id}}">
    {{-- @if($user->profilePic()->path != null)
        <img class="d-flex mr-3 rounded-circle" src="{{$user->profilePic()->path}}" alt="Profile Picture" height="54">
    @endif --}}
    <a href="users/{{$user->id}}" class="text-dark">{{$user->name}}</a>
    <hr/>
</div>
