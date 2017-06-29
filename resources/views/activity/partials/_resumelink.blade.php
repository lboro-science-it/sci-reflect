@if($resumeLink)

<a href="{{ $resumeLink }}">
@if($activity->pivot->current_page == 1)
Start
@else
Resume
@endif
</a>

@endif