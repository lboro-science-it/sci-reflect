<div class="row">
    <div class="col-md-12">
        @include('activity.linear.partials._current')
    </div>
</div>

@if($activityData->strongestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Strongest skills</h3>
            @foreach($activityData->strongestSkills as $skill)
                @include('skills.partials._horizontal', ['skill' => $skill])
            @endforeach
            ** view all (link sorted by strongest) **
        </div>
    </div>
@endif

@if($activityData->weakestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Improve your skills</h3>
            <p>Click for resources to help improve your weakest areas.</p>
            @foreach($activityData->weakestSkills as $skill)
                @include('skills.partials._improve', ['skill' => $skill])
            @endforeach
        </div>
    </div>
@endif

