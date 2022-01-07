<div class="user_search_result" id="user_search_result_{{$user->id}}">
    @if($user->profile_pic != null)
        <img class="rounded-circle m-2" src="{{url($user->profilePic->path)}}" alt="Profile Picture" height="54">
    @endif
    <a href="users/{{$user->id}}" class="text-dark">{{$user->name}}</a>
    @if($user->id !== auth()->user()->id)
        <button class="btn btn-primary ban" style="background-color: red" user="{{$user}}" admin="{{auth()->user()}}">
            @if($user->priv_stat !== 'Banned')
                Ban
            @else
                Unban
            @endif
        </button>
    @endif
    <hr/>
</div>
