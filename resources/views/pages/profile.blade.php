@extends('layouts.app')

@section('page-title', $user->name . ' | ')


@if($user->id == Auth::user()->id)
@section('profile-tab', 'selected')
@endif

@section('content')
<div class="container mb-3 p-4 profile">
    <div class="profile-pics">
        <div class="cover-picture">
            <img src="storage/images/blank-profile-picture.png" />
        </div>
        <div class="profile-picture">
            <img src="storage/images/blank-profile-picture.png" />
        </div>
        <div class="profile-name">
                {{ $user->name }}
        </div>
    </div>
    @if (Auth::user()->id == $user->id)
    <div class="profile-newpost">
        <h1 class="h3">New Post</h1>
        <!-- Include stylesheet -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <!-- Include the Quill library -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <!-- Create the editor container -->
        <form id="new-post" action="/api/user_content" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="background-color: white" id="editor"></div>
            <div class="d-flex mt-1">
                <div class="mb w-100">
                    <label for="images" class="form-label" hidden>Image Input</label>
                    <input class="form-control" name="images" type="file" accept="image/*" id="images" multiple>
                </div>
                <button class="btn btn-primary ms-1" type="submit">Post</button>
            </div>
        </form>
    </div>
    @endif
</div>

<div class="profile-posts">
    @foreach(Auth::user()->ownPosts() as $post)
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:70%;'])
        @endif
    @endforeach
</div>
<div class="profile-friends">
    <h1 class="profile-friends-title">Friends</h1>
</div>
<div class="profile-groups">
    <h1 class="profile-groups-title">Groups</h1>
</div>

@endsection
