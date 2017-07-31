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
                    @include('page.partials._save')
                    @include('page.partials._done')
                </div>
            </div>
        </div>

        {{-- Round Contents sidebar --}}
        {{-- todo: xs hide and put in hamburger instead --}}
        <div class="col-md-3 col-md-offset-1 col-md-pull-7 col-sm-4 col-sm-pull-8
        ">
            @include('page.linear.partials._sidebar', [
                'pages' => $pageData->sidebar
            ])
        </div>

    </div>
</form>