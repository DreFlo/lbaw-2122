<form action="{{route('comments.add')}}" method="POST" class="user_content_control_form">
    @csrf
    @method('POST')
    <div class="form-group" style="display: flex">
        <input type="hidden" name="parent_id" value="{{$parent->id}}">
        <input type="hidden" name="group_id" value="{{$parent->group_id}}">
        <label class="reply_box">
            <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
        </label>
    </div>
    <input type="submit" value="Reply" style="margin: 1%">
</form>
