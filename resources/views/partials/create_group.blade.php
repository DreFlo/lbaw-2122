<div class="post">
    <div class="form-group">
        <div class="font_group">Name:</div>
        <label title="Enter post text" class="create_post_form">
            <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
        </label>

        <label class="create_post_form">Cover Picture
            <input type="file" name="images[]" class="form-control" multiple>
        </label>

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
    </div>
    <a href='/create_g'>
        <button  type="button">Create</button>
    </a>
</div>