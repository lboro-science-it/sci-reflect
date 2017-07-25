<div class="row">
    <div class="col-md-12">
        @include('activity.linear.partials._current')
    </div>
</div>

@isset($activityData->strongestSkills)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Strongest skills</h3>
            @foreach($activityData->strongestSkills as $skill)
                @include('skills.partials._skill', ['skill' => $skill])
            @endforeach
            ** view all (link sorted by strongest) **
        </div>
    </div>
@endisset

@isset($activityData->weakestSkills)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Areas for improvement</h3>
            @foreach($activityData->weakestSkills as $skill)
                @include('skills.partials._skill', ['skill' => $skill, 'showDescription' => true])
            @endforeach
            ** view all (link sorted by weakest) **
        </div>
    </div>
@endisset

