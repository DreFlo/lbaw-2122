<div class="post">
    <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>
                <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
            </label>
        </div>
        <label>Images</label>
        <input type="file" name="images[]" class="form-control" multiple>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
