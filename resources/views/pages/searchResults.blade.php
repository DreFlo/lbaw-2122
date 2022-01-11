@extends('layouts.app')

@section('content')
    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab" aria-controls="pills-users" aria-selected="true">Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-groups-tab" data-bs-toggle="pill" data-bs-target="#pills-groups" type="button" role="tab" aria-controls="pills-groups" aria-selected="false">Groups</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-posts-tab" data-bs-toggle="pill" data-bs-target="#pills-posts" type="button" role="tab" aria-controls="pills-posts" aria-selected="false">Posts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-comments-tab" data-bs-toggle="pill" data-bs-target="#pills-comments" type="button" role="tab" aria-controls="pills-comments" aria-selected="false">Comments</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active bg-transparent" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            @each('partials.user_search_result', $users, 'user')
        </div>
        <div class="tab-pane fade bg-transparent" id="pills-groups" role="tabpanel" aria-labelledby="pills-groups-tab">
            @each('partials.group_search_result', $groups, 'group')
        </div>
        <div class="tab-pane fade bg-transparent" id="pills-posts" role="tabpanel" aria-labelledby="pills-posts-tab">
            @each('partials.post_search_result', $posts, 'post')
        </div>
        <div class="tab-pane fade bg-transparent" id="pills-comments" role="tabpanel" aria-labelledby="pills-comments-tab">Comments</div>
    </div>
@endsection
