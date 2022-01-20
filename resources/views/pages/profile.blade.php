@extends('layouts.app')

@section('page-title', $user->name . ' | ')

@if($user->id == optional(Auth::user())->id)
@section('profile-tab', 'selected')
@endif

@section('content')
@if($user->priv_stat == 'Anonymous')
    <h1 class="m-5">
        @if(optional(Auth::user())->id == $user->id)
            <script type="text/javascript">document.getElementById('logout-form').submit();</script>
        @endif
        Deleted Account
    </h1>
@else
    <div class="container mb-3 p-4 profile">
        <div class="profile-pics">
            <div class="cover-picture">
                @foreach($user->getCoverPic() as $pic)
                    <img src="{{url($pic->path)}}" alt={{$pic->alt}}>
                @endforeach
            </div>
            <div class="profile-picture">
                @foreach($user->getProfilePic() as $pic)
                    <img src="{{url($pic->path)}}" alt={{$pic->alt}}>
                @endforeach
            </div>
            <div class="profile-name">
                    {{ $user->name }}
            </div>
        </div>
    </div>

    @if(Auth::check())
        @if (optional(Auth::user())->id != $user->id && optional(Auth::user())->isFriend($user))
            <button class="btn btn-primary remove_friend" style="background-color: red" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Remove Friend</button>
        @elseif (optional(Auth::user())->id != $user->id && !(optional(Auth::user())->isFriend($user)))
            <button class="btn btn-primary send_request" style="background-color: green" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Send Friend Request</button>
        @endif
    @endif

    @if (optional(Auth::user())->id != $user->id && optional(Auth::user()->isAdmin()))
        <button class="btn btn-primary ban" style="background-color: red" user_id="{{$user->id}}" admin_id="{{auth()->user()->id}}">
            @if($user->priv_stat !== 'Banned')
                Ban
            @else
                Unban
            @endif
        </button>
    @endif

    @if(optional(Auth::user())-> id == $user->id)
        <div class="user_content_interaction_block" style="flex: auto; justify-self: center">
            <form action="{{route('users.destroy', $user)}}" method="POST" class="user_content_control_form">
                @csrf
                @method('DELETE')
                <button class="btn btn-primary" style="background-color: red" type="submit" title="Delete">Delete Account</button>
                <button class="btn btn-primary" type="button" onclick="location.href = '{{route('groups.create')}}';" title="Delete">Create Group</button>
                <button class="btn btn-primary" type="button" onclick="location.href = '{{route('posts.create')}}';" title="Delete">Create Post</button>
            </form>
        </div>
    @endif

    <div class="profile-posts">
        @foreach($user->ownPosts() as $post)
            @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
                @include('partials.post', ['post' => $post, 'style' => 'width:70%;'])
            @endif
        @endforeach
    </div>
@endif

@endsection
