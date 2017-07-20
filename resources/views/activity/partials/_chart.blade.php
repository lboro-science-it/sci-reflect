<h3>Current skills</h3>
@if(!is_null($activityData->chartData))
    @include('chart.partials._chart', ['chartData' => $activityData->chartData])
@else
    ** Insert placeholder empty chart for when they have no responses yet **
@endif