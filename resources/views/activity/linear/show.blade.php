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
                @include('skills.partials._horizontal', ['skill' => $skill])
            @endforeach
            ** view all (link sorted by strongest) **
        </div>
    </div>
@endisset

@isset($activityData->weakestSkills)
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Improve your skills</h3>
            <p>Click for resources to help improve your weakest areas.</p>
            @foreach($activityData->weakestSkills as $skill)
                <?php /*
                @include('skills.partials._vertical', ['skill' => $skill, 'showDescription' => true])
                */ ?>
                @include('skills.partials._improve', ['skill' => $skill])
            @endforeach
        </div>
    </div>
@endisset

