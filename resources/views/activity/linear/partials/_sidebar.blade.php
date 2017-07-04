@if($rounds->completed->count() > 0)
    <h4>Completed Rounds</h4>
    <ul>
        @foreach($rounds->completed as $completedRound)
            <li>
                {{ $completedRound->title }} ({{ $completedRound->completion }}%) ** link **
            </li>
        @endforeach
    </ul>
@endif
@if(!is_null($rounds->current))
    <h4>In Progress</h4>
    <ul>
        <li>
            {{ $rounds->current->title }} ({{ $rounds->current->completion }}%)
        </li>
    </ul>
@endif
@if($rounds->future->count() > 0)
    <h4>Coming Up</h4>
    <ul>
        @foreach($rounds->future as $futureRound)
            <li>
                {{ $futureRound->title }}
            </li>
        @endforeach
    </ul>
@endif