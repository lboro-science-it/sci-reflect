<h3>
    Contents
</h3>
<p>
    ** todo: **<br>
    <strike>add links</strike><br>
    display when viewed<br>
    <strike>display when completed</strike><br>
    display which have skills/questions<br>
    <strike>display currently being viewed</strike>
</p>
<ul>
    @foreach($pages as $page)
        <li>
            @if($page->current)
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <strong>
            @else
                <button type="submit" name="page" value="{{ $page->pageNumber }}" class="btn btn-pagelink">
            @endif
                {{ $page->title }}
                @if($page->complete)
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                @endif
            @if(!$page->current)
                </button>
            @else
                </strong>
            @endif

        </li>
    @endforeach
</ul>