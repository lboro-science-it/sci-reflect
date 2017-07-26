<div class="row">
    <div class="col-xs-4">
        <h4 style="margin-top: 0;">
            {{ $skill->title }}
        </h4>
    </div>
    <div class="
        @isset($improve)
            col-xs-5
        @else
            col-xs-8
        @endisset
    ">
        <div class="progress">
            <div class="progress-bar" 
                 role="progressbar" 
                 aria-valuenow="{{ $skill->rating }}" 
                 aria-valuemin="0" 
                 aria-valuemax="{{ $skill->max }}"
                 style="width: {{ $skill->rating / $skill->max * 100 }}%;
                        background-color: {{ $skill->category->color }};">
                {{ $skill->rating }}
            </div>
        </div>
    </div>
    @isset($improve)
        <div class="col-xs-3">
            <button class="btn btn-default" 
                    style="width: 100%;"
                    data-toggle="modal"
                    data-target="#skill-{{ $skill->id }}">
                Improve
            </button>
        </div>
        @include('skills.partials._modal', ['skill' => $skill])
    @endisset
</div>

@isset($showDescription)
    <div class="row">
        <div class="col-xs-12">
            {{ $skill->description }}
        </div>
    </div>
@endisset