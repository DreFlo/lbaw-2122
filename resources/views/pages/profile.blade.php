@extends('layouts.app')

@section('page-title', $user->name . ' | ')


@if($user->id == Auth::user()->id)
@section('profile-tab', 'selected')
@endif

@section('content')
<div class="container mb-3 p-4 profile">
    <div class="row p-2">
        <div class="col-1 profile-picture" style="background-image: url('{{URL::asset('storage/images/blank-profile-picture.png')}}')"></div>
        <div class="col flex-column d-flex">
            <div class="row h1 profile-namme">
                {{ $user->name }}
            </div>
        </div>
        <div class="col-1" style="text-align: end">
            <button class='post-options' type='button' id='user-options' data-bs-toggle='dropdown' aria-expanded='false'>
                <i class='fas fa-ellipsis-v'></i>
            </button>
            <ul class='dropdown-menu' aria-labelledby='user-options'>
                @if (Auth::user()->id == $user->id)
                <li><a class='dropdown-item' href='/profile/edit'>Edit Profile</a></li>
                @endif
            </ul>
        </div>
    </div>
    @if (Auth::user()->id == $user->id)
    <div class="row">
        <div class="accordion mt-3" id="notifications">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general-notifications" aria-expanded="false" aria-controls="collapseTwo">
                        <h1 class="h4">Notifications</h1>
                    </button>
                </h2>
                <div id="general-notifications"class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#notifications">
                    <div class="p-0">
                        <div class="alert d-flex m-0 justify-content-between" role="alert">
                            <span><i class='m-0 fas fa-comment mx-3'></i><a href="#">Jon Doe</a> Commented your <a href="#">Post</a></span>
                            <button type="button" class="btn-close" aria-label="Close"></button>
                        </div>
                        <div class="alert d-flex m-0 justify-content-between" role="alert">
                            <span><i class='m-0 fas fa-share mx-3'></i><a href="#">Jon Doe</a> Shared your <a href="#">Post</a></span>
                            <button type="button" class="btn-close" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 6em; margin-top: 1em">
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
<div id="posts">
    @each('partials.post', $posts, 'post')
</div>

@endsection
