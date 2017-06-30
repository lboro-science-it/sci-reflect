@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">

    </div>
    <div class="col-md-9">

        <form id="page-selections-form" action="{{ url('a/' . $activity->id . '/student/r/' . $pageData->roundNumber . '/p/' . $pageData->pageNumber) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('POST') }}

        @foreach($pageData->content as $contentItem)
            @if($contentItem instanceof \App\Skill)
                @include('page.linear.partials._skill', ['skill' => $contentItem, 'choices' => $pageData->choices, 'selections' => $pageData->selections])
            @elseif($contentItem instanceof \App\Block)
                @include('page.linear.partials._block', ['block' => $contentItem])
            @endif
        @endforeach

        <p>{{ $pageData->pageNumber }} / {{ $pageData->totalPages }}</p>

        <p>
        @if($pageData->hasPrev)
            <button type="submit" name="prev" value="prev" class="btn btn-primary">Prev</button>
        @endif
        </p>

        <p>
        @if($pageData->hasNext)
            <button type="submit" name="next" value="next" class="btn btn-primary">Next</button>
        @endif
        </p>

        <p>
        @if($pageData->hasDone)
            <button type="submit" name="done" value="done" class="btn btn-success">Done</button>
        @endif
        </p>

        </form>

    </div>
</div>


@endsection