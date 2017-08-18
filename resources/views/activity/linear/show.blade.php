<div class="row">
    <div class="col-md-12">
        @include('activity.linear.partials._current')
    </div>
</div>

@if($activityData->strongestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Strongest skills</h3>
        </div>
        <div class="panel-body">
            <div style="margin-top: 22px;">
                @foreach($activityData->strongestSkills as $skill)
                    <div class="col-sm-4">
                        @include('partials.ratings.pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
            <a href="{{ url('a/' . $activity->id . '/student/r/' . $activityData->rounds->completed->last()->round_number . '/chart/strongest') }}">View all</a>
        </div>
    </div>
@endif

@if($activityData->weakestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Improve your skills</h3>
        </div>
        <div class="panel-body">
            <p>Click for resources to help improve your weakest areas.</p>
            <div>
                @foreach($activityData->weakestSkills as $skill)
                    <div class="col-sm-4">
                        @include('partials.ratings.pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

