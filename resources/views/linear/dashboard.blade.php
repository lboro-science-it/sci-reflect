<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                @isset($activityData->rounds->current)
                    <h3>{{ $activityData->rounds->current->title }}</h3>
                @else
                    <h3>Activity complete!</h3>
                @endisset
            </div>
            <div class="panel-body">
                @isset($activityData->rounds->current)
                    @if($activityData->rounds->current->viewable)
                        @isset($activityData->roundContent)
                        <p>
                            {{-- Include a content block linked to the round --}}
                            {!! $activityData->roundContent !!}
                        </p>
                        @endisset

                        @isset($activityData->statusMessage)
                        <p>
                            {{-- Include a message related to completion status of round --}}
                            {{ $activityData->statusMessage }}
                        </p>
                        @endisset

                        @if($activityData->resumeLink)
                            {{-- Include required action buttons: start, resume, review/save --}}
                            <form action="{{ $activityData->resumeLink }}" method="POST">
                                {{ csrf_field() }}
                                
                                <button type="submit" name="resume" value="resume" class="btn btn-primary btn-lg">
                                    @if(Auth::user()->currentPage == 1)
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
                                    <button type="submit" name="done" value="done" class="btn btn-success btn-lg">
                                        Submit your responses
                                    </button>
                                @endif
                            </form>
                        @endif
                    @else
                        <button type="unavailable" class="btn btn-danger disabled">
                            {{-- Include a disabled button when round is not yet open, etc --}}
                            {{ $activityData->rounds->current->notViewableReason }}
                        </button>
                    @endif
                @else
                    <p>You have completed this activity. Well done! Now you can see how you progressed on your skills throughout...</p>
                @endisset
            </div>
        </div>
    </div>
</div>

@if($activityData->strongestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Strongest skills</h3>
        </div>
        <div class="panel-body">
            <div style="margin-top: 22px;">
                @foreach($activityData->strongestSkills as $skill)
                    <div class="col-sm-4">
                        @include('partials.ratings.pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
            <a href="{{ url('a/' . $activity->id . '/student/r/' . $activityData->rounds->completed->last()->round_number . '/chart/strongest') }}">View all</a>
        </div>
    </div>
@endif

@if($activityData->weakestSkills->count() > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Improve your skills</h3>
        </div>
        <div class="panel-body">
            <p>Click for resources to help improve your weakest areas.</p>
            <div>
                @foreach($activityData->weakestSkills as $skill)
                    <div class="col-sm-4">
                        @include('partials.ratings.pill', ['skill' => $skill, 'improve' => true])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

