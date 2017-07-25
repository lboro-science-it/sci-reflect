<div class="col-sm-4">
    <button type="button"
            class="btn btn-default" 
            data-toggle="modal"
            data-target="#skill-{{ $skill->id }}"
            style="height: 100%; 
                   width: 100%; 
                   margin: 2%; 
                   padding: 2%;">
        <h4>
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
    </button>
</div>

<div class="modal fade" id="skill-{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="skill-{{ $skill->id }}-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    Improve your skills at {{ $skill->title }}
                </div>
                <div class="modal-body">
                    Insert stuff about improving at {{ $skill->title }}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>