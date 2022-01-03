<div class="post">
    <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data" id="create_post_form">
        @csrf
        <div class="form-group">
            <label title="Enter post text" class="create_post_form">
                <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
            </label>
        </div>
        <lable class="create_post_form">Visibility
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
        </lable>
        <label class="create_post_form">Images
            <input type="file" name="images[]" class="form-control" multiple>
        </label>
        <label title="Search users to tag" id="tag_label" class="create_post_form">
            Tags
            <input type="text" class="tag_search_field">
            <button type="button" class="btn btn-primary tag_search_button">Search</button>
        </label>
        <button type="submit" class="btn btn-primary" style="margin: 1%">Create</button>
    </form>
</div>
