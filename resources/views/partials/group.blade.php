
<div class="cover_group">
    <img style="width:100%; height:100%" src="{{ url($group->coverPic->path) }}" class="cover_pic_profile" alt="{{ $group->coverPic->alt }}"> </img>
    <div class="name_group">
        <a href="/groups/{{ $group->id }} " class="font_group_name" >
            {{ $group->name }}
        </a>
    </div>
    @if($group->isModerator(auth()->user()))
    <a href='/groups/{{$group->id}}/edit_group'>
        <button class="leave_group" type="button">
            Edit Group
        </button>
    </a>
    @elseif($group->isMember(auth()->user()))
    <a href='/groups/{{$group->id}}/leave_group/{{auth()->user()->id}}'>
        <button class="leave_group" type="button">
            Leave Group 
        </button>
    </a>
    @endif
</div>


<div class="cont_group">

    <div class="posts_group">
        @if($group->isMember(auth()->user()))
            @include('partials.create_post', ['group' => $group, 'style' => 'width:98%'])
        @endif
        @foreach($group->posts as $post)
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
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
