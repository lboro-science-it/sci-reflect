@isset($activityData->chartData)
    <div class="panel panel-default">
        <div class="panel-body" style="padding: 20px;">
            <h3>Your skills</h3>
            @include('chart.partials._chart', ['chartData' => $activityData->chartData])
        </div>
    </div>
@endisset