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
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>

</div>