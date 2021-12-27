<div>
    <form method="POST" action="{{route('posts.store')}}">
        @csrf
        <div class="form-group">
            <label>Text</label>
            <textarea class="form-control" style="height:150px" name="text" placeholder="Enter Text"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
