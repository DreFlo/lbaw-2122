@if(Auth::check())
<div class="post_timeline">
    @foreach(auth()->user()->friendsAndGroupsPost() as $post)
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
        @endif
    @endforeach
</div>

<div class="groups_timeline">
    <table class="table_group">
    <caption class="groups_title"> Groups </a></caption>
    @foreach(auth()->user()->groups as $group)
        @if($group->priv_stat !== "Anonymous")
            <tr><td class="row_group" background="{{$group->coverPic->path}}"><a class="font_timeline" href='groups/{{ $group->id }}'>{{ $group->name }}</a></td></tr>
        @endif
    @endforeach
    </table>
</div>
@else
<div class="post_timeline">
    @foreach(\App\Models\Post::all() as $post)    
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
        @endif
    @endforeach
</div>
@endif