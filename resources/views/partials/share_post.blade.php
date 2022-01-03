<div class="post">
    <form method="POST" action="{{route('shares.store')}}" id="create_post_form">
        @csrf
        <div class="form-group">
            <label class="create_post_form">
                <textarea class="form-control" name="text" placeholder="Enter Text" required></textarea>
            </label>
        </div>
        <lable for="visibility" class="create_post_form">Visibility
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
        <label title="Search users to tag" id="tag_label" class="create_post_form">
            Tags
            <input type="text" class="tag_search_field">
            <button type="button" class="btn btn-primary tag_search_button">Search</button>
        </label>
        <input type="hidden" value="{{$post->id}}" name="post_id">
        <button type="submit" class="btn btn-primary" style="margin: 1%">Share</button>
    </form>
    <div class="post" id="post_{{$post->id}}" style="width: 98%">
        @include('partials.user_content', ['content' => $post->content])
        @if($post->hasImages())
            <div class="post_image_slideshow">
                @foreach($post->images() as $image)
                    <img src="{{asset($image->path)}}" class="post_image post_image_transition_fade" alt={{$image->alt}}>
                @endforeach
                @if(count($post->images()) > 1)
                    <a class="post_prev_image" onclick="plusPostSlides(-1)">&#10094;</a>
                    <a class="post_next_image" onclick="plusPostSlides(1)">&#10095;</a>
                @endif
            </div>
            <div style="text-align:center; margin-top: 5px">
                @if(count($post->images()) > 1)
                    @for($i = 1; $i <= count($post->images()); $i++)
                        <span class="dot" onclick="currentPostSlide({{$i}})"></span>
                    @endfor
                @endif
            </div>
        @endif
    </div>
</div>
