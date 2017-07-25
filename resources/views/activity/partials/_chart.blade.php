@isset($activityData->chartData)
    <div class="panel panel-default">
        <div class="panel-body" style="padding: 20px;">
            @if($activityData->rounds->completed->count() > 0)
                <h3>Your skills after {{ $activityData->rounds->completed->last()->title }}</h3>
            @else
                <h3>Your skills</h3>
            @endif
            @include('chart.partials._chart', ['chartData' => $activityData->chartData])
        </div>
    </div>
@endisset