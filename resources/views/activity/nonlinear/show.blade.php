<div class="row">
    @if(!is_null($activityData->rounds->current))
        <h3>{{ $activityData->rounds->current->title }}
            @if(!$activityData->rounds->current->viewable)
                <small>
                    {{ $activityData->rounds->current->notViewableReason }}
                </small>
            @endif
        </h3>
    @else
        <h3>Activity complete!</h3>
    @endif
</div>
<div class="row">
    @foreach($activityData->categories as $category)
    <div class="col-sm-4">
        @if($activityData->roundViewable)
            <a href="#" class="category-link">
        @endif
            <div class="panel panel-default">
                @if($activityData->roundViewable)
                    <?php $style = 'background-color: ' . $category->color . ';'; ?>
                @else
                    <?php $style = 'background-color: #d3d3d3;'; ?>
                @endif
                <div class="panel-heading" style="{{ $style }}"></div>
                <div class="panel-body">
                    <img src="{{ $category->icon_url }}">
                    <h4>{{ $category->name }}</h4>
                    <p>Total skills: {{ $category->totalSkills }}</p>
                    <div class="progress progress-reflect">
                        <div class="progress-bar" 
                             role="progressbar" 
                             aria-valuenow="{{ $category->decCompletion }}" 
                             aria-valuemin="0" 
                             aria-valuemax="1"
                             style="width: {{ $category->decCompletion / 1 * 100 }}%;
                                    background-color: {{ $category->color }};">
                            {{ $category->completion }}
                        </div>
                    </div>
                </div>
            </div>
        @if($activityData->roundViewable)
            </a>
        @endif
    </div>
    @endforeach
</div>
