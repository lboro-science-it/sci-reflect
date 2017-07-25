<div class="row">
    <div class="col-xs-4">
        {{ $skill->title }}
    </div>
    <div class="col-xs-7">
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
    <div class="col-xs-1">
        <a href="{{ $skill->info_link }}" target="_blank"><span class="glyphicon glyphicon-link pull-right"></span></a>
    </div>
</div>

@isset($showDescription)
    <div class="row">
        <div class="col-xs-12">
            {{ $skill->description }}
        </div>
    </div>
@endisset