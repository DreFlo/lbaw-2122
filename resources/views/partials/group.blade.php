
<div class="cover_group">
    <img style="width:100%; height:100%" src="{{ url($group->coverPic->path) }}" class="cover_pic_profile" alt="{{ $group->coverPic->alt }}"> </img>
    <div class="name_group">
        <a href="/groups/{{ $group->id }} " class="font_group">
            {{ $group->name }}
        </a>
    </div>
</div>

<div class="cont_group">
    <div class="posts_group">
        @foreach($group->posts as $post)
            @include('partials.post', ['post' => $post, 'style' => 'width:98%;'])
        @endforeach
    </div>
    <div class="groups_timeline">
        <div class="members_group">
            <p style="font-weight:bold; width: 100%"> Groups Members </p>
            <table class="table_group_members">
                @foreach($group->members as $member)
                <tr>
                    <td class="row_group" background="{{url($member->profilePic->path)}}">
                        <a class="font_group" href='/users/{{ $member->id }}'>
                            {{ $member->name }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
