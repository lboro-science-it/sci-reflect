<div class="panel panel-default"
    @isset($improve)
     role="button"
     data-toggle="modal"
     data-target="#skill-{{ $skill->id }}"
    @endisset
>
    <div class="panel-heading" 
         style="background-color: {{ $skill->category->color }};
                padding: 2.5px;">
    </div>
    <div class="panel-body" style="padding: 2%; margin: 0;">
        <h4 class="text-center">
            {{ $skill->title }}
        </h4>
        <div class="progress progress-reflect progress-min">
            <div class="progress-bar" 
                 role="progressbar" 
                 aria-valuenow="{{ $skill->rating }}" 
                 aria-valuemin="0" 
                 aria-valuemax="{{ $skill->max }}"
                 style="width: {{ $skill->percent }}%;
                        background-color: {{ $skill->background }};">
            </div>
        </div>
    </div>
</div>

@isset($improve)
@include('skills.partials._modal', ['skill' => $skill])
@endisset
