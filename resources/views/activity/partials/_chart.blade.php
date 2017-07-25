@isset($activityData->chartData)
    <div class="panel panel-default">
        <div class="panel-body" style="padding: 20px;">
            @if($activityData->rounds->completed->count() > 0)
                <h3>Your skills after 
                    <a href="{{ url('a/' . $activity->id . '/student/r/' . $activityData->rounds->completed->last()->round_number . '/chart') }}">
                        {{ $activityData->rounds->completed->last()->title }}
                    </a>
                </h3>
            @else
                <h3>Your skills</h3>
            @endif
            @include('chart.partials._chart', ['chartData' => $activityData->chartData])
        </div>
    </div>
@endisset