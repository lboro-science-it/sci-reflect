<ul class="list-group">
    @if($rounds->completed->count() > 0)
        <li class="list-group-item">
            <h3>Completed Rounds</h3>
        </li>
        @foreach($rounds->completed as $completedRound)
            <li class="list-group-item">
                <a href="{{ url('a/' . $activity->id . '/student/r/' . $completedRound->round_number . '/chart') }}">
                    {{ $completedRound->title }} ({{ $completedRound->completion }})
                </a>
            </li>
        @endforeach
    @endif
</ul>

<ul class="list-group">
    @if($rounds->future->count() > 0)
        <li class="list-group-item">
            <h3>Coming Up</h3>
        </li>
        @foreach($rounds->future as $futureRound)
            <li class="list-group-item">
                {{ $futureRound->title }}
            </li>
        @endforeach
    @endif
</ul>