<div class="members_page">
            
    <table class="table_group_members">
        <caption style="caption-side:top; text-align: center; font-weight: bold;"><a class="font_group" href="/groups/{{$group->id}}/members"> Group Members </a></caption>´
        @foreach($members->chunk(5) as $rowmembers)
        <tr>
            @foreach($rowmembers as $member)
            <td class="row_group">
                <a class="font_group" href='/users/{{ $member->id }}'>
                    
                    <img alt="profile_pic" src="{{url($member->profilePic->path)}}" style="width:150px; height:150px;">
                    </img>
                    <br>
                    {{ $member->name }}
                    
                </a>
                @if(Auth::check())
                    @if($group->isModerator(auth()->user()))
                        <a href='/groups/{{$group->id}}/leave_group/{{$member->id}}'>
                            <img alt="deleteUser" src="/storage/graphics/delete.png" style="width: 25px; height: 25px; margin-bottom: 3%;">
                            </img>
                        </a>
                    @endif
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>

</div>