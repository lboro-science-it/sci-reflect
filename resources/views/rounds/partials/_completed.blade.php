<ul class="list-group">
    @if($rounds->count() > 0)
        <li class="list-group-item">
            <h3>Completed Rounds</h3>
        </li>
        @foreach($rounds as $round)
            <li class="list-group-item">
                <a href="{{ url('a/' . $activity->id . '/student/r/' . $round->round_number . '/chart') }}">
                    {{ $round->title }} ({{ $round->completion }})
                </a>
            </li>
        @endforeach
    @endif
</ul>