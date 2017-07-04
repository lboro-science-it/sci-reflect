<div class="panel panel-default">
    <div class="panel-body">
        <ul>
            @foreach($pages as $page)
                <li>
                    <a href="{{ url('a/' . $activity->id . '/student/r/' . $pageData->roundNumber . '/p/' . $page->pageNumber) }}">
                        {{ $page->title }}
                    </a>
                    @if($page->complete)
                        ** COMPLETE **
                    @endif
                    @if($page->current)
                        ** CURRENT **
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>