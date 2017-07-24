<h3>
    Contents
</h3>

<div class="list-group">
    @foreach($pages as $page)
        @if($page->current)
            <div class="list-group-item active">
        @else
            <button type="submit" name="page" value="{{ $page->pageNumber }}" class="list-group-item">
        @endif
            @if($page->current)
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <strong>
            @endif
            {{ $page->title }}
            @if($page->current)
                </strong>
            @endif
            @if($page->complete)
                <span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span>
            @endif
            @if($page->hasIndicators)
                <span class="glyphicon glyphicon-eye-open pull-right" aria-hidden="true"></span>
            @endif
        @if(!$page->current)
            </button>
        @else
            </div>
        @endif
    @endforeach
</div>

<p>
    ** todo: **<br>
    <strike>add links</strike><br>
    <strike>display when completed</strike><br>
    display which have skills/questions<br>
    <strike>display currently being viewed</strike>
</p>