<article data-type="post" id="post-{{ $post->id }}" class="INSERT CLASS">
    <header class="post_header">
        <div class="post_author">
            <a href="/users/{{ $post->creator }}" class="INSERT CLASS">{{ $post->creator->name }}</a>            
        </div>
        @if ($post->group != null)
        <div class="post_group">
            <a href="/users/{{ $post->group }}" class="INSERT CLASS">in {{ $post->group->name }}</a>            
        </div>
        @endif
        @if ($post->edited)
        <strong>edited</string>
        @endif
        <div class="post_interactions">
            @if ($post->creator == Auth::user()->id)
            <a href="<EDIT POST LINK>">
                <img src="<INSERT EDIT BUTTON PATH>" alt="Edit">
            </a>
            <a href="<TAG POST LINK>">
                <img src="<INSERT TAG BUTTON PATH>" alt="Tag">
            </a>
            @endif
            <a href="<SHARE POST LINK>">
                <img src="<INSERT SHARE BUTTON PATH>" alt="Share">
            </a>
            <a href="<LIKE POST LINK>">
                <img src="<INSERT LIKE BUTTON PATH>" alt="Like">
            </a>
        </div>
    </header>
    <body>
    </body>
</article>