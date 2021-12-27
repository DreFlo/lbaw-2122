<div>
    <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Text</label>
            <textarea class="form-control" style="height:150px" name="text" placeholder="Enter Text" required></textarea>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
