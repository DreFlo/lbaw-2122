
<div class="cover_group">
    <img style="width:100%; height:100%" src="{{ url($group->coverPic->path) }}" class="cover_pic_profile" alt="{{ $group->coverPic->alt }}"> </img>
    <div class="name_group">
        <a href="/groups/{{ $group->id }} " class="font_group_name" >
            {{ $group->name }}
        </a>
    </div>
    @if(Auth::check())
        @if($group->isModerator(auth()->user()))
        <a href='/groups/{{$group->id}}/edit'>
            <button class="button_group" type="button">
                Edit Group
            </button>
        </a>
        @elseif($group->isMember(auth()->user()))
        <a href='/groups/{{$group->id}}/leave_group/{{auth()->user()->id}}'>
            <button class="button_group" type="button">
                Leave Group 
            </button>
        </a>
        @elseif($group->priv_stat === 'Public')
        <a href='/groups/{{$group->id}}/add_member/{{auth()->user()->id}}'>
            <button class="button_group" type="button">
                Join Group 
            </button>
        </a>
        @elseif($group->priv_stat === 'Private')
            @if(auth()->user()->hasRequestedGroup($group))
                <div class="button_group">
                    <div class="button_text_group">
                        Already Requested
                    </div>
                </div>
            @else
                <form action="{{route('group.request')}}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}"></input>
                    <input type="hidden" name="group_id" value="{{$group->id}}"></input>
                    <button type="submit" title="Request" class="button_group">Request Join</button>
                </form>
            @endif
        @endif
    @endif
</div>

@if($group->priv_stat === 'Public' || optional(auth()->user())->inGroup($group))
<div class="cont_group">

    <div class="posts_group">
        @if(Auth::check())
            @if($group->isMember(auth()->user()))
                @include('partials.create_post', ['group' => $group, 'style' => 'width:98%'])
            @endif
        @endif
        @foreach($posts as $post)
            @if(\Illuminate\Support\Facades\Gate::allows('view-content', $post->content))
                @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
            @endif
        @endforeach
    </div>

    <div class="members_group">

        <table class="table_group_members">
            <caption style="caption-side:top; text-align: center; font-weight: bold;"><a class="font_group" href="/groups/{{$group->id}}/members"> Group Members </a></caption>
            @foreach($members->chunk(2) as $rowmembers)
            <tr>
                @foreach($rowmembers as $member)
                <td class="row_group">
                    <a class="font_group" href='/users/{{ $member->id }}'>
                        
                        <img alt="profile_pic" src="{{url($member->profilePic->path)}}" style="width:150px; height:150px;">
                        </img>
                        <br>
                        {{ $member->name }}
                        
                        
                    </a>
                </td>
                @endforeach
            </tr>
            @endforeach
        </table>

    </div>
</div>

@elseif($group->priv_stat === 'Private')
<div class="private_group">
    Private Group
</div>
@endif