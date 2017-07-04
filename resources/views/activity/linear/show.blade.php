<div class="row">
    <div class="col-md-3">
        @if($activityData->rounds->completed->count() > 0)
            <h4>Completed Rounds</h4>
            <ul>
                @foreach($activityData->rounds->completed as $completedRound)
                    <li>
                        {{ $completedRound->title }} ({{ $completedRound->completion }}%) ** link **
                    </li>
                @endforeach
            </ul>
        @endif
        @if(!is_null($activityData->rounds->current))
            <h4>In Progress</h4>
            <ul>
                <li>
                    {{ $activityData->rounds->current->title }} ({{ $activityData->rounds->current->completion }}%)
                </li>
            </ul>
        @endif
        @if($activityData->rounds->future->count() > 0)
            <h4>Coming Up</h4>
            <ul>
                @foreach($activityData->rounds->future as $futureRound)
                    <li>
                        {{ $futureRound->title }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="col-md-9">
        Student view. Todo: <br>
        Explanatory Text | Current Round | Current Round % Complete | Previous Rounds | Forthcoming Rounds | Current Skills | Strongest Skills | Skills to Improve<br>
        @include('activity.partials._resumelink', ['resumeLink' => $activityData->resumeLink])
    </div>
</div>



