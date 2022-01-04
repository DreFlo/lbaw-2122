<div class="post">
    <form method="POST" action="{{route('user_content.update', $content)}}" enctype="application/x-www-form-urlencoded">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label>
                <textarea class="form-control" name="text" required>{{$content->text}}</textarea>
            </label>
        </div>
        <lable for="visibility">Visibility</lable>
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
        <button type="submit" class="btn btn-primary">Edit</button>
    </form>
</div>
