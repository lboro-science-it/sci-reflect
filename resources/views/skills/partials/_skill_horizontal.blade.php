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
            <span class="pull-right">
                <button class="btn btn-default">
                    Improve
                </button>
                <!--
                    <a href="{{ $skill->info_link }}" target="_blank">
                        <span class="glyphicon glyphicon-link pull-right"></span>
                    </a>
                -->
            </span>
        </div>
    @endisset
</div>

@isset($showDescription)
    <div class="row">
        <div class="col-xs-12">
            {{ $skill->description }}
        </div>
    </div>
@endisset