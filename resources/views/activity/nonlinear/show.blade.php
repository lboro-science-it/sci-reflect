<div class="row">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-4">
                @include('activity.partials._sidebar', ['rounds' => $activityData->rounds])
            </div>
            <div class="col-md-8">
                @include('activity.nonlinear.partials._current')
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @include('activity.partials._chart')
    </div>
</div>
