<div class="row">
    <div class="col-md-3">
        @include('activity.linear.partials._sidebar', ['rounds' => $activityData->rounds])
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                @include('activity.linear.partials._current')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Your strongest skills...</h3>
                <p>** include top 3 (or whatever number) skills with progress bars **</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Want to improve?</h3>
                <p>** include bottom 3 (or whatever number) skills + links to further info **</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @include('activity.linear.partials._chart')
    </div>
</div>