@extends('layouts.student')

@section('title')

{{ $pageData->roundTitle }}: {{ $pageData->pageTitle }}

@endsection

@section('content')

    <form id="page-selections-form" action="{{ url('a/' . $activity->id . '/linear/r/' . $pageData->roundNumber . '/p/' . $pageData->pageNumber) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('POST') }}

        <div class="row">
            {{-- Page Content --}}
            <div class="col-md-7 col-md-push-4 col-sm-8 col-sm-push-4">
                <div class="panel panel-default">
                    <div class="panel-body">

                        @foreach($pageData->content as $contentItem)
                            @if($contentItem instanceof \App\Skill)

                                @include('partials.skills.table', [
                                    'skill' => $contentItem,
                                    'choices' => $pageData->choices,
                                    'descriptors' => $pageData->descriptors,
                                    'selections' => $pageData->selections
                                ])

                            @elseif($contentItem instanceof \App\Block)

                                <div>{!! $contentItem->content !!}</div>

                            @endif
                        @endforeach

                        <p>{{ $pageData->pageNumber }} / {{ $pageData->totalPages }}</p>

                        @if($pageData->hasPrev)
                            <button type="submit" name="prev" value="prev" class="btn btn-primary">Prev</button>
                        @endif

                        @if($pageData->hasNext)
                            <button type="submit" name="next" value="next" class="btn btn-primary">Next</button>
                        @endif

                        @if($pageData->hasSave)
                            <button type="submit" name="save" value="save" class="btn btn-success">Save</button>
                        @endif
                        
                        @if($pageData->hasDone)
                            <button type="submit" name="done" value="done" class="btn btn-success">Done</button>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Round Contents sidebar --}}
            {{-- todo: xs hide and put in hamburger instead --}}
            <div class="col-md-3 col-md-offset-1 col-md-pull-7 col-sm-4 col-sm-pull-8">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Contents</h3>
                    </div>
                    <div class="list-group">
                        @foreach($pageData->sidebar as $page)
                            @if($page->current)
                                <div class="list-group-item active">
                            @else
                                <button type="submit" name="page" value="{{ $page->pageNumber }}" class="list-group-item">
                            @endif

                            @if($page->current)
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <strong>
                            @endif

                            {{ $page->title }}
                            
                            @if($page->current)
                                </strong>
                            @endif
                            
                            @if($page->complete)
                                <span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span>
                            @elseif($page->hasIndicators)
                                <span class="glyphicon glyphicon-eye-open pull-right" aria-hidden="true"></span>
                            @endif

                            @if(!$page->current)
                                </button>
                            @else
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection