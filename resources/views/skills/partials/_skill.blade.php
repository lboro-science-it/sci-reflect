<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-xs-4">
            {{ $skill->title }}
        </div>
        <div class="col-xs-8">
            <div class="progress progress-reflect">
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
    </div>
</div>