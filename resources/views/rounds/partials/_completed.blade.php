@if($rounds->count() > 0)
    <ul class="list-group">
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
    </ul>
@endif
