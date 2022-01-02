@if(Auth::check())
<div class="post_timeline">
    @foreach(auth()->user()->friendsAndGroupsPost() as $post)
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
        @endif
    @endforeach
</div>

<div class="groups_timeline">
    <table  class="table_group">
        <tr><th style="font-size: xx-large"> Groups </th></tr>
    @foreach(auth()->user()->groups as $group)
        <tr><td class="row_group" background="{{$group->coverPic->path}}"><a class="font_timeline" href='groups/{{ $group->id }}'>{{ $group->name }}</a></td></tr>
    @endforeach
    </table>
</div>
@else
<div class="post_timeline">
    @foreach(\App\Models\Post::all()->sort(function ($a, $b) {
            if ($a->content->timestamp === $b->content->timestamp) return 0;
            return $a->content->timestamp < $b->content->timestamp ? 1 : -1;
        }) as $post)    
        @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
        @endif
    @endforeach
</div>
@endif