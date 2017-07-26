<div class="modal fade" id="skill-{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="skill-{{ $skill->id }}-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    Improve your skills at {{ $skill->title }}
                </div>
                <div class="modal-body">
                    <p>
                        {!! $skill->block->content !!}
                    </p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>