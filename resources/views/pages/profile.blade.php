@extends('layouts.app')

@section('page-title', $user->name . ' | ')


@if($user->id == optional(Auth::user())->id)
@section('profile-tab', 'selected')
@endif

@section('content')
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
    @if(Auth::check())
        @if (optional(Auth::user())->id != $user->id && optional(Auth::user())->isFriend($user))
            <button class="btn btn-primary remove_friend" style="background-color: red" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Remove Friend</button>
        @elseif (optional(Auth::user())->id != $user->id && !(optional(Auth::user())->isFriend($user)))
            <button class="btn btn-primary send_request" style="background-color: green" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Send Friend Request</button>
        @endif
        @if(auth()->user()->isAdmin() && $user->id !== auth()->user()->id)
            <button class="btn btn-primary ban" style="background-color: red" user_id="{{$user->id}}" admin_id="{{auth()->user()->id}}">
                @if($user->priv_stat !== 'Banned')
                    Ban
                @else
                    Unban
                @endif
            </button>
        @endif
    @endif
    @if (optional(Auth::user())->id == $user->id)
        <div class="post-profile">
            <h1 style="margin-left:10px" class="font_group">Create Post!</h1>
            <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data" id="create_post_form">
                @csrf
                <div class="form-group">
                    <label title="Enter post text" class="create_post_form">
                        <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
                    </label>
                </div>
                    <input type="hidden" name="visibility" value="{{Auth::user()->priv_stat}}">
                    <lable class="create_post_form">Visibility
                        @if (auth()->user()->priv_stat === 'Public')
                            <select name="visibility" id="visibility_selector">
                                <option value="Public" selected>Public</option>
                                <option value="Private">Private</option>
                            </select>
                        @else
                            <select name="visibility" id="visibility_selector">
                                <option value="Public">Public</option>
                                <option value="Private" selected>Private</option>
                            </select>
                        @endif
                    </lable>
                <label class="create_post_form">Images
                    <input type="file" name="images[]" class="form-control" multiple>
                </label>
                <label title="Search users to tag" id="tag_label" class="create_post_form">
                    Tags
                    <input type="text" class="tag_search_field">
                    <button type="button" class="btn btn-primary tag_search_button">Search</button>
                </label>
                    <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                <button type="submit" class="btn btn-primary" style="margin: 1%">Create</button>
            </form>
        </div>
    @endif
</div>

<div class="profile-posts">
    @foreach($user->ownPosts() as $post)
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:70%;'])
        @endif
    @endforeach
</div>

@endsection
