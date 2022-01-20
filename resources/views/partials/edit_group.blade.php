
@if($group->isModerator(auth()->user()) || auth()->user()->isAdmin)
<div class="edit_group_page">
    <div class="edit_info_group">
        <form method="post" action="/group/edit">
            @csrf
            @method('PATCH')
            <div class="gedit_name_group">
                <label for="name" class="edit_name_group">Name</label>
                <input name="name" type="text" class="form-control" id="name" value="{{ $group->name }}">
                
            </div>
            <div class="gedit_pic_group">
                <label for="cover-image" class="edit_pic_group">Cover Picture</label>
                <br>
                <input name="cover-image" type="file" class="form-control-file" accept="image/*" id="cover-picture">
            </div>
            <div class="edit_button_group">
                <button type="submit" class="edit_text_group" title="Edit">Edit Group</button>
            </div>
        </form>
    </div>
    <div class="edit_delete_group">
        <form action="/groups/{{$group->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete_text_group" title="Delete">Delete Group</button>
        </form>
    </div>

    <div class="notifications_group">
        <a href="/groups/{{$group->id}}/notifications">
            <button type="button" class="notification_text_group"> Notifications </button>
        </a>
    </div>
    <label title="Search users to invite" id="invite">
            Tags
            <input type="text" class="invite_search_field">
            <button type="button" class="btn btn-primary invite_search_button">Search</button>
        </label>
</div>
@endif