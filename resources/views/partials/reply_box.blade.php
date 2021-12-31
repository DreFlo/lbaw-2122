<form action="{{route('comment.add')}}" method="POST" class="post_control_form">
    @csrf
    @method('POST')
    <div class="form-group" style="display: flex">
        <input type="hidden" name="parent_id" value="{{$parent_id}}">
        <label class="reply_box">
            <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
        </label>
    </div>
    <input type="submit" value="Reply">
</form>
