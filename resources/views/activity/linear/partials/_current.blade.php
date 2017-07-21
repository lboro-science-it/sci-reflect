<div class="panel panel-default">
    <div class="panel-body">
        @isset($activityData->rounds->current)
            <h3>{{ $activityData->rounds->current->title }}</h3>
            @if($activityData->rounds->current->viewable)
                @isset($activityData->roundContent)
                    {!! $activityData->roundContent !!}
                @endisset
                ** encouraging message e.g. "Well done on reflecting..." "Welcome back, pick up where you left off...", "There are only a few days..." etc
                @include('activity.linear.partials._start_resume', ['resumeLink' => $activityData->resumeLink])
            @else
                <button type="unavailable" class="btn btn-danger disabled">
                    {{ $activityData->rounds->current->notViewableReason }}
                </button>
            @endif
        @else
            <h3>Activity complete!</h3>
            <p>You have completed this activity. Well done! Now you can see how you progressed on your skills throughout...</p>
        @endisset
    </div>
</div>