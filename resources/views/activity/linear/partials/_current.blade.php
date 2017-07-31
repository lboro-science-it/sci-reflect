<div class="panel panel-default">
    <div class="panel-heading">
        @isset($activityData->rounds->current)
            <h3>{{ $activityData->rounds->current->title }}</h3>
        @else
            <h3>Activity complete!</h3>
        @endisset
    </div>
    <div class="panel-body">
        @isset($activityData->rounds->current)
            @if($activityData->rounds->current->viewable)
                @isset($activityData->roundContent)
                <p>
                    {{-- Include a content block linked to the round --}}
                    {!! $activityData->roundContent !!}
                </p>
                @endisset

                @isset($activityData->statusMessage)
                <p>
                    {{-- Include a message related to completion status of round --}}
                    {{ $activityData->statusMessage }}
                </p>
                @endisset

                @if($activityData->resumeLink)
                    {{-- Include required action buttons: start, resume, review/save --}}
                    @include('activity.linear.partials._start_resume')
                @endif
            @else
                <button type="unavailable" class="btn btn-danger disabled">
                    {{-- Include a disabled button when round is not yet open, etc --}}
                    {{ $activityData->rounds->current->notViewableReason }}
                </button>
            @endif
        @else
            <p>You have completed this activity. Well done! Now you can see how you progressed on your skills throughout...</p>
        @endisset
    </div>
</div>

{{-- 
<div class="panel panel-default">
    <div class="panel-body">
        ** todo: insert stats about current round **
        <li>Total skills in round: {{ $activityData->totalSkills }}</li>
        <li>Total skills user has responded to: {{ $activityData->totalSkillsResponded }}</li>
        <li>Total skills user responded max to: {{ $activityData->totalSkillsRespondedMax }}</li>
        <li>Total skills user responded min to: {{ $activityData->totalSkillsRespondedMin }}</li>
        <li>Total skills almost maxed: {{ $activityData->totalSkillsRespondedNearMax }}</li>
        <li>Current page title: {{ $activityData->currentPageTitle }}</li>
    </div>
</div>
--}}