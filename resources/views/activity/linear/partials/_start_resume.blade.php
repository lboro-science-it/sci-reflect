@if($resumeLink)
    <form action="{{ $resumeLink }}" method="POST">
        {{ csrf_field() }}
        <button type="submit" name="resume" value="resume" class="btn btn-primary">
        @if($activity->pivot->current_page == 1)
            Start
        @else
            Resume ({{ $activityData->rounds->current->completion }})
        @endif
        </button>
    </form>
@endif