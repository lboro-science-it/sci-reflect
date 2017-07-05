@extends('layouts.app')

@section('title')

{{ $pageData->pageTitle }}

@endsection

@section('content')

<form id="page-selections-form" action="{{ url('a/' . $activity->id . '/student/r/' . $pageData->roundNumber . '/p/' . $pageData->pageNumber) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('POST') }}

    <div class="row">
        <div class="col-md-3">
            @include('page.linear.partials._sidebar', [
                'pages' => $pageData->sidebar
            ])
        </div>
        <div class="col-md-9">



            @foreach($pageData->content as $contentItem)
                @if($contentItem instanceof \App\Skill)
                    @include('page.linear.partials._skill', [
                        'skill' => $contentItem,
                        'choices' => $pageData->choices,
                        'selections' => $pageData->selections
                    ])
                @elseif($contentItem instanceof \App\Block)
                    @include('page.partials._block', [
                        'block' => $contentItem
                    ])
                @endif
            @endforeach

            <p>{{ $pageData->pageNumber }} / {{ $pageData->totalPages }}</p>

            @include('page.partials._prev')
            @include('page.partials._next')
            @include('page.partials._done')

        </div>
    </div>
</form>

@endsection