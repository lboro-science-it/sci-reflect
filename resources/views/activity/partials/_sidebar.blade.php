<div class="panel panel-default">
    <div class="panel-body">
        @if($rounds->completed->count() > 0)
            <h3>Completed Rounds</h3>
            <div class="list-group">
                @foreach($rounds->completed as $completedRound)
                    <div class="list-group-item">
                        <a href="{{ url('a/' . $activity->id . '/student/r/' . $completedRound->round_number . '/chart') }}">
                            {{ $completedRound->title }} ({{ $completedRound->completion }})
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if($rounds->future->count() > 0)
            <h3>Coming Up</h3>
            <div class="list-group">
                @foreach($rounds->future as $futureRound)
                    <div class="list-group-item">
                        {{ $futureRound->title }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>