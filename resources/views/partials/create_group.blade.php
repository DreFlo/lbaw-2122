<div class="post">
    <a style="margin-left:10px" class="font_group" href="/groups/create">
        Create Group!
    </a>
    <form method="POST" action="{{route('groups.store')}}">
        @csrf
        <div class="form_group">
            <label title="Enter group name" class="create_group_form">
                <textarea class="form-control" name="name" placeholder="Enter Name" required></textarea>
            </label>
        </div>
        <label class="create_group_form">Cover Picture
            <input type="file" name="image" class="form-control">
        </label>
        <label class="create_group_form">Visibility
            @if (auth()->user()->priv_stat === 'Public')
                <select name="visibility" id="visibility_selector">
                    <option value="Public" selected>Public</option>
                    <option value="Private">Private</option>
                </select>
            @else
                <select name="visibility" id="visibility_selector">
                    <option value="Public">Public</option>
                    <option value="Private" selected>Private</option>
                </select>
            @endif
        </label>
        
        <button style="margin: 1%" class="btn btn-primary" type="submit">Create</button>
    </form>

</div>
