<div class="row">
    @include('activity.linear.partials._current')
</div>

<div class="row">
    <div class="col-md-6">
        @isset($activityData->strongestSkills)
            <div class="row">
                <div class="col-md-12">
                    <h3>Your strongest skills...</h3>
                    
                    @foreach($activityData->strongestSkills as $skill)
                        @include('skills.partials._skill', ['skill' => $skill])
                    @endforeach
                    
                </div>
            </div>
        @endisset
    </div>
    <div class="col-md-6">
        @isset($activityData->weakestSkills)
            <div class="row">
                <div class="col-md-12">
                    <h3>Want to improve?</h3>
                    
                    @foreach($activityData->weakestSkills as $skill)
                        @include('skills.partials._skill', ['skill' => $skill])
                    @endforeach
                </div>
            </div>
        @endisset
    </div>
</div>