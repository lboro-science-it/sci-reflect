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
                            background-color: {{ $skill->category->color }};">
                    {{ $skill->rating }}
                </div>
            </div>
        </div>
        <div class="col-xs-10">
            <h3>{{ $skill->title }} <a href="{{ $skill->info_link }}" target="_blank"><span class="glyphicon glyphicon-link pull-right"></span></a></h3>
            @isset($showDescription)
                <p>{{ $skill->description }}</p>
            @endisset
        </div>
    </div>
</div>