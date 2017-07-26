<div class="row">
    <div class="col-md-12">
        @include('activity.linear.partials._current')
    </div>
</div>

@if($activityData->strongestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Strongest skills</h3>
            <div style="margin-top: 22px;">
                @foreach($activityData->strongestSkills as $skill)
                    <div class="col-sm-4">
                        @include('skills.partials._pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
            ** view all (link sorted by strongest) **
        </div>
    </div>
@endif

@if($activityData->weakestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Improve your skills</h3>
            <p>Click for resources to help improve your weakest areas.</p>
            <div>
                @foreach($activityData->weakestSkills as $skill)
                    <div class="col-sm-4">
                        @include('skills.partials._pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

