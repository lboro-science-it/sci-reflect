<div class="row">
    <div class="col-md-3">
        @include('activity.linear.partials._sidebar', ['rounds' => $activityData->rounds])
    </div>
    <div class="col-md-9">
        Student view. Todo: <br>
        Explanatory Text | Current Round | Current Round % Complete | Previous Rounds | Forthcoming Rounds | Current Skills | Strongest Skills | Skills to Improve<br>
        @include('activity.partials._resumelink', ['resumeLink' => $activityData->resumeLink])
    </div>
</div>



