@if(!is_null($activityData->rounds->current))
    <h3>{{ $activityData->rounds->current->title }}</h3>
    @if($activityData->rounds->current->viewable)
        @include('activity.linear.partials._start_resume', ['resumeLink' => $activityData->resumeLink])
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
    Student view. Todo:
</p>
<ul>
    <li>** Explanatory Text (from Round model?) **</li>
    <li>** Encouraging messages etc "Well done on reflecting on x..."
        "Pick up where you left off..."
        "There are only a few days left..." **</li>
</ul>
