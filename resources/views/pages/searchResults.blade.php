@extends('layouts.app')

@section('content')
    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab" aria-controls="pills-users" aria-selected="true">Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-groups-tab" data-bs-toggle="pill" data-bs-target="#pills-groups" type="button" role="tab" aria-controls="pills-groups" aria-selected="false">Groups</button>
        </li>
        @if($posts != null && $comments != null)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-posts-tab" data-bs-toggle="pill" data-bs-target="#pills-posts" type="button" role="tab" aria-controls="pills-posts" aria-selected="false">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-comments-tab" data-bs-toggle="pill" data-bs-target="#pills-comments" type="button" role="tab" aria-controls="pills-comments" aria-selected="false">Comments</button>
            </li>
        @endif
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active bg-transparent" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            <div class="filters">
               <form class="form-inline my-2 my-lg-0" action="/search" method="get">
                   <p><input name="search" class="invisible" value={{$request->input('search')}}></p>
                   <a>Sort by:</a>
                   <select class="form-select input-sm" name="sort_user" aria-label="Sort type">
                       <option value="rank" @if(!$request->has('sort_user') || ($request->input('sort_user') == 'rank')) selected @endif>Default</option>
                       <option value="name" @if($request->input('sort_user') == 'name') selected @endif>Alphabetical Order</option>
                   </select>
                   <hr>
                   <a>Order:</a>
                   <div class="form-check">
                       <input class="form-check-input" type="radio" name="sort_order_user" id="sort-asc" value="asc"
                              @if(($request->input('sort_order_user') == 'asc') || (!$request->has('sort_order_user'))) checked @endif>
                       <label class="form-check-label" for="flexRadioDefault1">
                           Ascending
                       </label>
                   </div>
                   <div class="form-check">
                        <input class="form-check-input" type="radio" name="sort_order_user" id="sort-desc" value="desc"
                        @if($request->input('sort_order_user') == 'desc') checked @endif>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Descending
                        </label>
                   </div>
                   <hr>
                   @if(\Illuminate\Support\Facades\Auth::check())
                       <a>Show:</a>
                       <div class="form-check">
                           <input class="form-check-input" type="radio" name="sort_user_type" id="sort-asc" value="all"
                                  @if(($request->input('sort_user_type') == 'all') || (!$request->has('sort_user_type'))) checked @endif>
                           <label class="form-check-label" for="flexRadioDefault1">
                               All users
                           </label>
                       </div>
                       <div class="form-check">
                           <input class="form-check-input" type="radio" name="sort_user_type" id="sort-desc" value="public"
                                  @if($request->input('sort_user_type') == 'public') checked @endif>
                           <label class="form-check-label" for="flexRadioDefault2">
                               Public users only
                           </label>
                       </div>
                       <div class="form-check">
                           <input class="form-check-input" type="radio" name="sort_user_type" id="sort-desc" value="private"
                                  @if($request->input('sort_user_type') == 'private') checked @endif>
                           <label class="form-check-label" for="flexRadioDefault2">
                               Private users only
                           </label>
                       </div>
                       <div class="form-check">
                           <input class="form-check-input" type="radio" name="sort_user_type" id="sort-desc" value="friends"
                                  @if($request->input('sort_user_type') == 'friends') checked @endif>
                           <label class="form-check-label" for="flexRadioDefault2">
                               Friends only
                           </label>
                       </div>
                       <hr>
                   @endif
                       <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >Apply</button>
               </form>
            </div>
            <div class="results">
                @each('partials.user_search_result', $users, 'user')
            </div>
        </div>
        <div class="tab-pane fade bg-transparent" id="pills-groups" role="tabpanel" aria-labelledby="pills-groups-tab">
            <div class="filters">
                <form class="form-inline my-2 my-lg-0" action="/search" method="get">
                    <p><input name="search" class="invisible" value={{$request->input('search')}}></p>

                    <a>Sort by:</a>
                    <select class="form-select input-sm" name="sort_group" aria-label="Sort type">
                        <option value="rank" @if(!$request->has('sort_group') || ($request->input('sort_group') == 'rank')) selected @endif>Default</option>
                        <option value="name" @if($request->input('sort_group') == 'name') selected @endif>Alphabetical Order</option>
                    </select>
                    <hr>
                    <a>Order:</a>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sort_order_group" id="sort-asc" value="asc"
                               @if(($request->input('sort_order_group') == 'asc') || (!$request->has('sort_order_group'))) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Ascending
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sort_order_group" id="sort-desc" value="desc"
                               @if($request->input('sort_order_group') == 'desc') checked @endif>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Descending
                        </label>
                    </div>
                    <hr>
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <a>Show:</a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_group_type" id="sort-asc" value="all"
                                   @if(($request->input('sort_group_type') == 'all') || (!$request->has('sort_group_type'))) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                                All groups
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_group_type" id="sort-desc" value="public"
                                   @if($request->input('sort_group_type') == 'public') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Public groups only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_group_type" id="sort-desc" value="private"
                                   @if($request->input('sort_group_type') == 'private') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Private groups only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_group_type" id="sort-desc" value="member"
                                   @if($request->input('sort_group_type') == 'member') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                My groups
                            </label>
                        </div>
                        <hr>
                    @endif
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >Apply</button>
                </form>
            </div>
            <div class="results">
                @each('partials.group_search_result', $groups, 'group')
            </div>
        </div>
        @if($posts != null && $comments != null)
            <div class="tab-pane fade bg-transparent" id="pills-posts" role="tabpanel" aria-labelledby="pills-posts-tab">
                <div class="filters">
                    <form class="form-inline my-2 my-lg-0" action="/search" method="get">
                        <p><input name="search" class="invisible" value={{$request->input('search')}}></p>

                        <a>Sort by:</a>
                        <select class="form-select input-sm" name="sort_post" aria-label="Sort type">
                            <option value="rank" @if(!$request->has('sort_post') || ($request->input('sort_post') == 'rank')) selected @endif>Default</option>
                            <option value="timestamp" @if($request->input('sort_post') == 'timestamp') selected @endif>Time posted</option>
                            <option value="likes" @if($request->input('sort_post') == 'likes') selected @endif>Likes</option>
                        </select>
                        <hr>
                        <a>Order:</a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order_post" id="sort-asc" value="asc"
                                   @if(($request->input('sort_order_post') == 'asc') || (!$request->has('sort_order_post'))) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Ascending
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order_post" id="sort-desc" value="desc"
                                   @if($request->input('sort_order_post') == 'desc') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Descending
                            </label>
                        </div>
                        <hr>
                        <a>Show:</a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_post_type" id="sort-asc" value="all"
                                   @if(($request->input('sort_post_type') == 'all') || (!$request->has('sort_post_type'))) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                                All posts
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_post_type" id="sort-desc" value="public"
                                   @if($request->input('sort_post_type') == 'public') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Public posts only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_post_type" id="sort-desc" value="private"
                                   @if($request->input('sort_post_type') == 'private') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Private posts only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_post_type" id="sort-desc" value="friends"
                                   @if($request->input('sort_post_type') == 'friends') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Friends posts
                            </label>
                        </div>
                        <hr>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >Apply</button>
                    </form>
                </div>
                <div class="results">
                    @each('partials.post_search_result', $posts, 'post')
                </div>
            </div>
            <div class="tab-pane fade bg-transparent" id="pills-comments" role="tabpanel" aria-labelledby="pills-comments-tab">
                <div class="filters">
                    <form class="form-inline my-2 my-lg-0" action="/search" method="get">
                        <p><input name="search" class="invisible" value={{$request->input('search')}}></p>

                        <a>Sort by:</a>
                        <select class="form-select input-sm" name="sort_comment" aria-label="Sort type">
                            <option value="rank" @if(!$request->has('sort_comment') || ($request->input('sort_comment') == 'rank')) selected @endif>Default</option>
                            <option value="timestamp" @if($request->input('sort_comment') == 'timestamp') selected @endif>Time posted</option>
                            <option value="likes" @if($request->input('sort_comment') == 'likes') selected @endif>Likes</option>
                        </select>
                        <hr>
                        <a>Order:</a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order_comment" id="sort-asc" value="asc"
                                   @if(($request->input('sort_order_comment') == 'asc') || (!$request->has('sort_order_comment'))) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Ascending
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order_comment" id="sort-desc" value="desc"
                                   @if($request->input('sort_order_comment') == 'desc') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Descending
                            </label>
                        </div>
                        <hr>
                        <a>Show:</a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_comment_type" id="sort-asc" value="all"
                                   @if(($request->input('sort_comment_type') == 'all') || (!$request->has('sort_comment_type'))) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                                All comments
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_comment_type" id="sort-desc" value="public"
                                   @if($request->input('sort_comment_type') == 'public') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Public comments only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_comment_type" id="sort-desc" value="private"
                                   @if($request->input('sort_comment_type') == 'private') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Private comments only
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_comment_type" id="sort-desc" value="friends"
                                   @if($request->input('sort_comment_type') == 'friends') checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Friends comments
                            </label>
                        </div>
                        <hr>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >Apply</button>
                    </form>
                </div>
                <div class="results">
                    @each('partials.comment_search_result', $comments, 'comment')
                </div>
            </div>
        @endif
    </div>
@endsection
