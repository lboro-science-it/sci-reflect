<div class="panel panel-default">
    <div class="panel-body">
        @isset($activityData->rounds->current)
            <h3>{{ $activityData->rounds->current->title }}</h3>
            @if($activityData->rounds->current->viewable)
                @isset($activityData->roundContent)
                ** todo: insert encouragement messages / personalisation **
                <div>
                    {!! $activityData->roundContent !!}
                </div>
                @endisset

                @if($activityData->resumeLink)
                    @include('activity.linear.partials._start_resume')
                @endif
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

<div class="panel panel-default">
    <div class="panel-body">
        ** todo: insert stats about current round **
        <li>Total skills in round</li>
        <li>Total skills user has responded to</li>
        <li>Total skills user responded max to</li>
        <li>Total skills user responded min to</li>
        <li>Total skills almost maxed</li>
        <li>Current page title</li>
    </div>
</div>