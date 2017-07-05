@if(!is_null($rounds->current))
    <h3>In Progress</h3>
    <ul>
        <li>
            {{ $rounds->current->title }} ({{ $rounds->current->completion }}%)
        </li>
    </ul>
@endif

@if($rounds->completed->count() > 0)
    <h3>Completed Rounds</h3>
    <ul>
        @foreach($rounds->completed as $completedRound)
            <li>
                {{ $completedRound->title }} ({{ $completedRound->completion }}%) ** link to chart / stats **
            </li>
        @endforeach
    </ul>
@endif

@if($rounds->future->count() > 0)
    <h3>Coming Up</h3>
    <ul>
        @foreach($rounds->future as $futureRound)
            <li>
                {{ $futureRound->title }}
            </li>
        @endforeach
    </ul>
@endif