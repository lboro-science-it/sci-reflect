<div class="col-sm-6">
    <div class="row">
        <div class="col-sm-1 col-xs-2">
            <div class="progress progress-bar-vertical">
                <div class="progress-bar"
                     role="progressbar"
                     aria-valuenow="{{ $skill->rating }}"
                     aria-valuemin="0"
                     aria-valuemax="{{ $skill->max }}"
                     style="height: {{ $skill->rating / $skill->max * 100 }}%;
                            background-color: {{ $category->color }};">
                    {{ $skill->rating }}
                </div>
            </div>
        </div>
        <div class="col-xs-10">
            <h3>{{ $skill->title }}
                <button class="btn btn-default" 
                        style="width: 100%;"
                        data-toggle="modal"
                        data-target="#skill-{{ $skill->id }}">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
            </h3>
            @isset($showDescription)
                <p>{{ $skill->description }}</p>
            @endisset
        </div>
    </div>
</div>

@include('skills.partials._modal', ['skill' => $skill])