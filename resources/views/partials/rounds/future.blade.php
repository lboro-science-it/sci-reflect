@if($rounds->count() > 0)
    <ul class="list-group">
        <li class="list-group-item">
            <h3>Coming Up</h3>
        </li>
        @foreach($rounds as $round)
            <li class="list-group-item">
                {{ $round->title }}
            </li>
        @endforeach
    </ul>
@endif
