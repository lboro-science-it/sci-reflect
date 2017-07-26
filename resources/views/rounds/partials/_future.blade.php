<ul class="list-group">
    @if($rounds->count() > 0)
        <li class="list-group-item">
            <h3>Coming Up</h3>
        </li>
        @foreach($rounds as $round)
            <li class="list-group-item">
                {{ $round->title }}
            </li>
        @endforeach
    @endif
</ul>