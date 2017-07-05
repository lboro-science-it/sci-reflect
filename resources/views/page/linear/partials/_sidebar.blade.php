<h3>
    Contents <small>** todo **</small>
</h3>
<ul>
    @foreach($pages as $page)
        <li>
            @if($page->current)
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            @endif
            <a href="{{ url('a/' . $activity->id . '/student/r/' . $pageData->roundNumber . '/p/' . $page->pageNumber) }}">
                {{ $page->title }}
            </a>
            @if($page->complete)
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            @endif
        </li>
    @endforeach
</ul>