<form action="{{ $activityData->resumeLink }}" method="POST">
    {{ csrf_field() }}
    <button type="submit" name="resume" value="resume" class="btn btn-primary">
    @if($activity->pivot->current_page == 1)
        Start
    @else
        @if($activityData->hasDone)
            Review your responses
        @else
            Resume ({{ $activityData->rounds->current->completion }})
        @endif
    @endif
    </button>
    @if($activityData->hasDone)
    <button type="submit" name="done" value="done" class="btn btn-success">
        Submit your responses
    </button>
    @endif
</form>