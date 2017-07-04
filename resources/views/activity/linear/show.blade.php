<div class="row">
    <div class="col-md-3">
        @include('activity.linear.partials._sidebar', ['rounds' => $activityData->rounds])
    </div>
    <div class="col-md-6">
        @if(!is_null($activityData->rounds->current))
            <h3>{{ $activityData->rounds->current->title }}</h3>
            @if($activityData->rounds->current->viewable)
                @include('activity.linear.partials._resume', ['resumeLink' => $activityData->resumeLink])
            @else
                <button type="unavailable" class="btn btn-danger disabled">
                    {{ $activityData->rounds->current->notViewableReason }}
                </button>
            @endif
        @else
            <h3>Activity complete!</h3>
            <p>You have completed this activity. ** Add links to view previous charts, skills, etc **</p>
        @endif
        <p>
            Student view. Todo: <br>
            Explanatory Text | 
            Well done finishing x.. pick up where you left off... | 
            Current Skills | 
            Strongest Skills | 
            Skills to Improve
        </p>
    </div>
    <div class="col-md-3">
        @if(!is_null($activityData->chartData))
            <h3>
                Current skills
            </h3>
            @include('chart.partials._chart', ['chartData' => $activityData->chartData])
        @endif
    </div>
</div>



