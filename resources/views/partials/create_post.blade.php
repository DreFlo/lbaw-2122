<div class="post">
    <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data" id="create_post_form">
        @csrf
        <div class="form-group">
            <label>
                <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
            </label>
        </div>
        <lable for="visibility">Visibility</lable>
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
        <label>Images</label>
        <input type="file" name="images[]" class="form-control" multiple>
        <label id="tag_label">
            Tags
            <input type="text" class="tag_search_field">
            <button type="button" class="btn btn-primary tag_search_button">Search</button>
        </label>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
