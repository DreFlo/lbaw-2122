<div class="post">
    <form method="POST" action="{{route('user_content.update', $content)}}" enctype="application/x-www-form-urlencoded">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label title="Enter post text" class="create_post_form">
                <textarea class="form-control" name="text" placeholder="Enter Text" required>{{$content->text}}</textarea>
            </label>
        </div>
        <label for="visibility" class="create_post_form">Visibility</label>
        @if ($content->priv_stat === 'Public')
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
        <button type="submit" class="btn btn-primary" style="margin: 1%">Edit</button>
    </form>
</div>
